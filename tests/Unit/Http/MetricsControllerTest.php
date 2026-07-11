<?php

namespace Tests\Unit\Http;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MetricsControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_metrics_endpoint_returns_prometheus_text(): void
    {
        User::factory()->count(3)->create();

        $response = $this->get('/api/metrics');

        $response->assertOk();
        $this->assertStringContainsString('text/plain', (string) $response->headers->get('Content-Type'));
        $response->assertSee('# TYPE healthai_users_total gauge', false);
        $response->assertSee('healthai_users_total 3', false);
    }

    public function test_metrics_endpoint_exposes_users_by_role(): void
    {
        User::factory()->create()->assignRole('admin');

        $this->get('/api/metrics')
            ->assertOk()
            ->assertSee('healthai_users_by_role{role="admin"} 1', false);
    }
}
