<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

/**
 * Canonical stored values are English (aligned with the /register contract and
 * the ML service). label() exposes the French wording for display.
 */
enum ActivityLevel: string implements HasLabel
{
    case Sedentary = 'sedentary';
    case Moderate = 'moderate';
    case Active = 'active';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Sedentary => 'Sédentaire',
            self::Moderate => 'Moyennement actif',
            self::Active => 'Actif',
        };
    }

    /**
     * Multiplier applied to the BMR to obtain the TDEE (Harris-Benedict).
     */
    public function tdeeMultiplier(): float
    {
        return match ($this) {
            self::Sedentary => 1.2,
            self::Moderate => 1.55,
            self::Active => 1.725,
        };
    }

    /**
     * Tolerant parser: accepts the canonical EN values plus legacy FR wording
     * (Sedentaire / Moyennement Actif(ve) / Actif(ve)). Order matters:
     * "moyennement actif" contains "actif", so moderate must be matched first.
     * Returns null when unrecognized.
     */
    public static function fromLegacy(?string $value): ?self
    {
        if ($value === null || trim($value) === '') {
            return null;
        }

        $normalized = strtolower(trim($value));

        if ($enum = self::tryFrom($normalized)) {
            return $enum;
        }

        return match (true) {
            str_contains($normalized, 'sédent'),
            str_contains($normalized, 'sedent') => self::Sedentary,
            str_contains($normalized, 'moyen'),
            str_contains($normalized, 'modér'),
            str_contains($normalized, 'moder') => self::Moderate,
            str_contains($normalized, 'actif'),
            str_contains($normalized, 'active') => self::Active,
            default => null,
        };
    }
}
