<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

/**
 * Canonical stored values are French (aligned with the /register contract and
 * the ML service). fromLegacy() normalizes casing (e.g. "Poids du Corps").
 */
enum ExerciseCategory: string implements HasLabel, LegacyNormalizable
{
    case Musculation = 'Musculation';
    case Cardio = 'Cardio';
    case PoidsDuCorps = 'Poids du corps';

    public function getLabel(): string
    {
        return $this->value;
    }

    /**
     * Tolerant parser: case-insensitive match on the canonical FR values.
     * Returns null when unrecognized.
     */
    public static function fromLegacy(?string $value): ?self
    {
        if ($value === null || trim($value) === '') {
            return null;
        }

        return match (strtolower(trim($value))) {
            'musculation' => self::Musculation,
            'cardio' => self::Cardio,
            'poids du corps' => self::PoidsDuCorps,
            default => null,
        };
    }
}
