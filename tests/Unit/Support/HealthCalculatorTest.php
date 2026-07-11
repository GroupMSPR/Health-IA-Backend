<?php

namespace Tests\Unit\Support;

use App\Enums\ActivityLevel;
use App\Enums\Gender;
use App\Support\HealthCalculator;
use PHPUnit\Framework\TestCase;

class HealthCalculatorTest extends TestCase
{
    private HealthCalculator $calc;

    protected function setUp(): void
    {
        parent::setUp();
        $this->calc = new HealthCalculator;
    }

    public function test_bmi_uses_height_not_weight(): void
    {
        // Regression I1: 75.5kg / 1.80m^2 = 23.3 (must divide HEIGHT by 100).
        $this->assertSame(23.3, $this->calc->bmi(75.5, 180));
    }

    public function test_bmi_null_on_missing_inputs(): void
    {
        $this->assertNull($this->calc->bmi(null, 180));
        $this->assertNull($this->calc->bmi(75.5, null));
        $this->assertNull($this->calc->bmi(75.5, 0));
    }

    public function test_bmr_male_formula(): void
    {
        // 88.36 + 13.4*75.5 + 4.8*180 - 5.7*26 = 1815.86
        $this->assertEqualsWithDelta(1815.86, $this->calc->bmr(Gender::Homme, 75.5, 180, 26), 0.001);
    }

    public function test_bmr_female_formula(): void
    {
        // 447.6 + 9.2*60 + 3.1*165 - 4.3*30 = 1382.1
        $this->assertEqualsWithDelta(1382.1, $this->calc->bmr(Gender::Femme, 60, 165, 30), 0.001);
    }

    public function test_bmr_null_for_autres(): void
    {
        $this->assertNull($this->calc->bmr(Gender::Autres, 75.5, 180, 26));
    }

    public function test_bmr_null_on_missing_inputs(): void
    {
        $this->assertNull($this->calc->bmr(null, 75.5, 180, 26));
        $this->assertNull($this->calc->bmr(Gender::Homme, null, 180, 26));
        $this->assertNull($this->calc->bmr(Gender::Homme, 75.5, 180, null));
    }

    public function test_tdee_applies_activity_multiplier(): void
    {
        // BMR 1815.86 * 1.725 (active)
        $this->assertEqualsWithDelta(1815.86 * 1.725, $this->calc->tdee(Gender::Homme, ActivityLevel::Active, 75.5, 180, 26), 0.001);
    }

    public function test_tdee_null_activity_falls_back_to_sedentary(): void
    {
        $this->assertEqualsWithDelta(1815.86 * 1.2, $this->calc->tdee(Gender::Homme, null, 75.5, 180, 26), 0.001);
    }

    public function test_daily_caloric_target_subtracts_default_deficit(): void
    {
        // round(1815.86 * 1.725 - 400) = round(2732.3585) = 2732
        $this->assertSame(2732, $this->calc->dailyCaloricTarget(Gender::Homme, ActivityLevel::Active, 75.5, 180, 26));
    }

    public function test_daily_caloric_target_custom_deficit(): void
    {
        $this->assertSame(3132, $this->calc->dailyCaloricTarget(Gender::Homme, ActivityLevel::Active, 75.5, 180, 26, 0));
    }

    public function test_daily_caloric_target_null_for_autres(): void
    {
        $this->assertNull($this->calc->dailyCaloricTarget(Gender::Autres, ActivityLevel::Active, 75.5, 180, 26));
    }
}
