<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Exercise;
use App\Models\Food;
use App\Models\HealthMetric;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

/**
 * Application metrics in the Prometheus text exposition format, for the
 * existing Prometheus/Grafana stack. All values are gauges computed at scrape
 * time, so the endpoint stays stateless (no counter storage / Redis needed).
 */
class MetricsController extends Controller
{
    public function index(Request $request): Response
    {
        // In production the endpoint is disabled unless a matching token is sent.
        if (app()->isProduction()) {
            $token = (string) config('metrics.token');
            abort_unless($token !== '' && hash_equals($token, (string) $request->bearerToken()), 403);
        }

        return response($this->render(), 200, [
            'Content-Type' => 'text/plain; version=0.0.4; charset=utf-8',
        ]);
    }

    private function render(): string
    {
        $lines = [];

        $this->metric($lines, 'healthai_app_info', 'Static application info (always 1).', [
            [['environment' => (string) config('app.env')], 1],
        ]);

        $this->metric($lines, 'healthai_users_total', 'Total number of users.', [
            [[], User::count()],
        ]);

        $this->metric($lines, 'healthai_users_by_role', 'Number of users per role.', array_map(
            fn (string $role): array => [
                ['role' => $role],
                User::whereHas('roles', fn ($q) => $q->where('name', $role))->count(),
            ],
            ['admin', 'coach', 'user'],
        ));

        $entities = [
            'healthai_foods_total' => ['Total number of foods.', Food::count()],
            'healthai_exercises_total' => ['Total number of exercises.', Exercise::count()],
            'healthai_posts_total' => ['Total number of posts.', Post::count()],
            'healthai_comments_total' => ['Total number of comments.', Comment::count()],
            'healthai_health_metrics_total' => ['Total number of health metrics.', HealthMetric::count()],
            'healthai_likes_total' => ['Total number of post likes.', DB::table('likes')->count()],
        ];

        foreach ($entities as $name => [$help, $value]) {
            $this->metric($lines, $name, $help, [[[], $value]]);
        }

        return implode("\n", $lines)."\n";
    }

    /**
     * Append one metric family (single HELP/TYPE header + N samples).
     *
     * @param  array<int, string>  $lines
     * @param  array<int, array{0: array<string, string>, 1: int|float}>  $samples
     */
    private function metric(array &$lines, string $name, string $help, array $samples): void
    {
        $lines[] = "# HELP {$name} {$help}";
        $lines[] = "# TYPE {$name} gauge";

        foreach ($samples as [$labels, $value]) {
            $lines[] = $name.$this->labels($labels).' '.$value;
        }
    }

    /**
     * @param  array<string, string>  $labels
     */
    private function labels(array $labels): string
    {
        if ($labels === []) {
            return '';
        }

        $parts = [];
        foreach ($labels as $key => $value) {
            $escaped = str_replace(['\\', '"'], ['\\\\', '\\"'], $value);
            $parts[] = $key.'="'.$escaped.'"';
        }

        return '{'.implode(',', $parts).'}';
    }
}
