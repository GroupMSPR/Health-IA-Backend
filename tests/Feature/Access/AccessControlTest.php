<?php

namespace Tests\Feature\Access;

use App\Models\Food;
use App\Models\HealthMetric;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

/**
 * Covers the lomkit access-control layer (Controls + Global/Own perimeters).
 * Roles/permissions come from the insert_default_roles_and_permissions migration.
 */
class AccessControlTest extends TestCase
{
    use RefreshDatabase;

    private function user(string $role): User
    {
        $user = User::factory()->create();
        $user->assignRole($role);

        return $user;
    }

    /**
     * @return array<int, string>
     */
    private function idsFrom(TestResponse $response): array
    {
        return collect($response->json('data') ?? [])->pluck('id')->all();
    }

    // ---- UserResource: Own perimeter (a user only reaches their own record) ----

    public function test_unauthenticated_cannot_search_users(): void
    {
        $this->postJson('/api/users/search', [])->assertUnauthorized();
    }

    public function test_user_search_returns_only_their_own_record(): void
    {
        $me = $this->user('user');
        $other = $this->user('user');

        $response = $this->actingAs($me, 'sanctum')->postJson('/api/users/search', []);

        $response->assertSuccessful();
        $ids = $this->idsFrom($response);
        $this->assertContains($me->id, $ids);
        $this->assertNotContains($other->id, $ids, 'A user must not see other users through search.');
    }

    public function test_admin_search_returns_all_users(): void
    {
        $admin = $this->user('admin');
        $u1 = $this->user('user');
        $u2 = $this->user('user');

        $response = $this->actingAs($admin, 'sanctum')->postJson('/api/users/search', []);

        $response->assertSuccessful();
        $ids = $this->idsFrom($response);
        $this->assertContains($u1->id, $ids);
        $this->assertContains($u2->id, $ids);
    }

    public function test_user_cannot_update_another_users_record(): void
    {
        $me = $this->user('user');
        $other = $this->user('user');
        $originalName = $other->first_name;

        $this->actingAs($me, 'sanctum')->postJson('/api/users/mutate', [
            'mutate' => [[
                'operation' => 'update',
                'key' => $other->id,
                'attributes' => ['first_name' => 'Hacked'],
            ]],
        ]);

        // The other record is outside the Own perimeter: the update must not apply.
        $this->assertDatabaseMissing('users', ['id' => $other->id, 'first_name' => 'Hacked']);
        $this->assertDatabaseHas('users', ['id' => $other->id, 'first_name' => $originalName]);
    }

    // ---- HealthMetricResource: Own perimeter scoped to user_id + ownership ----

    public function test_user_sees_only_their_own_health_metrics(): void
    {
        $me = $this->user('user');
        $other = $this->user('user');
        $mine = HealthMetric::factory()->create(['user_id' => $me->id]);
        $theirs = HealthMetric::factory()->create(['user_id' => $other->id]);

        $response = $this->actingAs($me, 'sanctum')->postJson('/api/health-metrics/search', []);

        $response->assertSuccessful();
        $ids = $this->idsFrom($response);
        $this->assertContains($mine->id, $ids);
        $this->assertNotContains($theirs->id, $ids, 'A user must not see other users health metrics.');
    }

    public function test_user_can_create_their_own_health_metric(): void
    {
        $me = $this->user('user');

        $response = $this->actingAs($me, 'sanctum')->postJson('/api/health-metrics/mutate', [
            'mutate' => [[
                'operation' => 'create',
                'attributes' => [
                    'weight' => 70,
                    'avg_bpm' => 80,
                    'max_bpm' => 150,
                    'resting_bpm' => 60,
                    'steps_count' => 5000,
                    'sleep_time' => '07:30:00',
                    'calories_burned' => 300,
                    'active_minute' => 45,
                ],
            ]],
        ]);

        $response->assertSuccessful();
        // user_id is forced server-side to the authenticated user.
        $this->assertDatabaseHas('health_metrics', [
            'user_id' => $me->id,
            'steps_count' => 5000,
        ]);
    }

    public function test_admin_sees_all_health_metrics(): void
    {
        $admin = $this->user('admin');
        $u1 = $this->user('user');
        $u2 = $this->user('user');
        $m1 = HealthMetric::factory()->create(['user_id' => $u1->id]);
        $m2 = HealthMetric::factory()->create(['user_id' => $u2->id]);

        $response = $this->actingAs($admin, 'sanctum')->postJson('/api/health-metrics/search', []);

        $response->assertSuccessful();
        $ids = $this->idsFrom($response);
        $this->assertContains($m1->id, $ids);
        $this->assertContains($m2->id, $ids);
    }

    // ---- FoodResource: Global perimeter gated by spatie permissions ----

    public function test_user_can_search_foods(): void
    {
        $me = $this->user('user'); // has view-foods
        Food::factory()->create(['name' => 'Banane']);

        $this->actingAs($me, 'sanctum')->postJson('/api/foods/search', [])->assertSuccessful();
    }

    public function test_user_cannot_create_food(): void
    {
        $me = $this->user('user'); // lacks create-foods

        $response = $this->actingAs($me, 'sanctum')->postJson('/api/foods/mutate', [
            'mutate' => [[
                'operation' => 'create',
                'attributes' => [
                    'name' => 'Interdit XYZ',
                    'category' => 'Fruit',
                    'calories' => 100,
                    'protein' => 1,
                    'carbohydrates' => 20,
                    'fat' => 0.5,
                    'fiber' => 2,
                    'sugars' => 15,
                    'sodium' => 5,
                    'cholesterol' => 0,
                ],
            ]],
        ]);

        $response->assertForbidden();
        $this->assertDatabaseMissing('foods', ['name' => 'Interdit XYZ']);
    }

    public function test_admin_can_create_food(): void
    {
        $admin = $this->user('admin'); // has create-foods

        $response = $this->actingAs($admin, 'sanctum')->postJson('/api/foods/mutate', [
            'mutate' => [[
                'operation' => 'create',
                'attributes' => [
                    'name' => 'Autorise XYZ',
                    'category' => 'Fruit',
                    'calories' => 100,
                    'protein' => 1,
                    'carbohydrates' => 20,
                    'fat' => 0.5,
                    'fiber' => 2,
                    'sugars' => 15,
                    'sodium' => 5,
                    'cholesterol' => 0,
                ],
            ]],
        ]);

        $response->assertSuccessful();
        $this->assertDatabaseHas('foods', ['name' => 'Autorise XYZ']);
    }
}
