<?php

namespace App\Policies;

use App\Access\Controls\HealthMetricControl;
use App\Models\HealthMetric;


class HealthMetricPolicy
{
    protected string $control = HealthMetricControl::class;
}
