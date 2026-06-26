<?php

namespace Tests\Feature;

use App\Models\Constraint;
use App\Models\Exercise;
use App\Models\Goal;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use MSPR2\SdkIA\Handlers\IllegalExercisesHandler;
use Tests\TestCase;

class IllegalExercisesHandlerTest extends TestCase
{
    use RefreshDatabase;

    private IllegalExercisesHandler $handler;

    public function setUp(): void
    {
        parent::setUp();
        $this->handler = new IllegalExercisesHandler();
    }

    public function test_exercise_is_illegal_when_shares_constraint_with_user(): void
    {
        $user = User::factory()->create();
        $exercise = Exercise::factory()->create();
        $constraint = Constraint::factory()->create();

        $user->constraints()->attach($constraint->getKey());
        $exercise->constraints()->attach($constraint->getKey());

        $this->assertFalse($this->handler->isLegal($exercise, $user));
    }

    public function test_exercise_is_legal_when_no_shared_constraint(): void
    {
        $user = User::factory()->create();
        $exercise = Exercise::factory()->create();

        $userConstraint = Constraint::factory()->create();
        $exerciseConstraint = Constraint::factory()->create();

        $user->constraints()->attach($userConstraint->getKey());
        $exercise->constraints()->attach($exerciseConstraint->getKey());

        $this->assertTrue($this->handler->isLegal($exercise, $user));
    }

    public function test_exercise_is_illegal_when_no_common_goal_and_user_has_goals(): void
    {
        $user = User::factory()->create();
        $exercise = Exercise::factory()->create();

        $userGoal = Goal::factory()->create(['goal' => 'Goal user ' . uniqid()]);
        $exerciseGoal = Goal::factory()->create(['goal' => 'Goal exercise ' . uniqid()]);

        $user->goals()->attach($userGoal->getKey());
        $exercise->goals()->attach($exerciseGoal->getKey());

        $this->assertFalse($this->handler->isLegal($exercise, $user));
    }

    public function test_exercise_is_legal_when_user_has_no_goals(): void
    {
        $user = User::factory()->create();
        $exercise = Exercise::factory()->create();

        $goal = Goal::factory()->create(['goal' => 'Goal ' . uniqid()]);
        $exercise->goals()->attach($goal->getKey());

        $this->assertTrue($this->handler->isLegal($exercise, $user));
    }

    public function test_exercise_is_legal_when_shares_goal_with_user(): void
    {
        $user = User::factory()->create();
        $exercise = Exercise::factory()->create();

        $commonGoal = Goal::factory()->create(['goal' => 'Goal commun ' . uniqid()]);

        $user->goals()->attach($commonGoal->getKey());
        $exercise->goals()->attach($commonGoal->getKey());

        $this->assertTrue($this->handler->isLegal($exercise, $user));
    }
}
