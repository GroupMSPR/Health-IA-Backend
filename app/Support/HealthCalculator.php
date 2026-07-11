<?php

namespace App\Support;

use App\Enums\ActivityLevel;
use App\Enums\Gender;

/**
 * Single source of truth for the health metrics previously duplicated across
 * RegisterController, UserResource, the UpdateUserWeightAndBmi listener and the
 * UserFactory. Formulas preserved from the original implementation:
 *   - BMI  = weight(kg) / height(m)^2
 *   - BMR  = Harris-Benedict (per gender)
 *   - TDEE = BMR * activity multiplier
 */
class HealthCalculator
{
    /**
     * Default daily caloric deficit subtracted from the TDEE to derive the
     * targeted daily intake (weight-loss oriented default).
     */
    public const DEFAULT_CALORIC_DEFICIT = 400;

    /**
     * Body Mass Index, rounded to 2 decimals. Null if inputs are missing/invalid.
     */
    public function bmi(?float $weightKg, ?float $heightCm): ?float
    {
        if (! $weightKg || ! $heightCm) {
            return null;
        }

        $heightM = $heightCm / 100;

        if ($heightM <= 0) {
            return null;
        }

        return round($weightKg / ($heightM ** 2), 2);
    }

    /**
     * Basal Metabolic Rate (Harris-Benedict). Null when it cannot be computed
     * (missing inputs, or a gender without a defined formula).
     */
    public function bmr(?Gender $gender, ?float $weightKg, ?float $heightCm, ?int $age): ?float
    {
        if ($gender === null || ! $weightKg || ! $heightCm || $age === null) {
            return null;
        }

        return match ($gender) {
            Gender::Homme => 88.36 + (13.4 * $weightKg) + (4.8 * $heightCm) - (5.7 * $age),
            Gender::Femme => 447.6 + (9.2 * $weightKg) + (3.1 * $heightCm) - (4.3 * $age),
            Gender::Autres => null,
        };
    }

    /**
     * Total Daily Energy Expenditure. A null activity level falls back to the
     * sedentary multiplier (preserves the previous default behavior).
     */
    public function tdee(?Gender $gender, ?ActivityLevel $level, ?float $weightKg, ?float $heightCm, ?int $age): ?float
    {
        $bmr = $this->bmr($gender, $weightKg, $heightCm, $age);

        if ($bmr === null) {
            return null;
        }

        $multiplier = ($level ?? ActivityLevel::Sedentary)->tdeeMultiplier();

        return $bmr * $multiplier;
    }

    /**
     * Targeted daily caloric intake = TDEE minus a deficit, rounded to an int.
     * Null when the TDEE cannot be computed.
     */
    public function dailyCaloricTarget(
        ?Gender $gender,
        ?ActivityLevel $level,
        ?float $weightKg,
        ?float $heightCm,
        ?int $age,
        int $deficit = self::DEFAULT_CALORIC_DEFICIT,
    ): ?int {
        $tdee = $this->tdee($gender, $level, $weightKg, $heightCm, $age);

        if ($tdee === null) {
            return null;
        }

        return (int) round($tdee - $deficit);
    }
}
