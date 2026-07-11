<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

/**
 * Canonical stored values are French (aligned with the /register contract).
 * Use fromLegacy() to normalize mixed inputs (EN from Filament, casing, etc.).
 */
enum Gender: string implements HasLabel
{
    case Homme = 'Homme';
    case Femme = 'Femme';
    case Autres = 'Autres';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Homme => 'Homme',
            self::Femme => 'Femme',
            self::Autres => 'Autre',
        };
    }

    /**
     * Tolerant parser: accepts the canonical FR values plus known legacy/EN
     * variants (male/female/other) and casing. Returns null when unrecognized.
     */
    public static function fromLegacy(?string $value): ?self
    {
        if ($value === null || trim($value) === '') {
            return null;
        }

        return match (strtolower(trim($value))) {
            'homme', 'male', 'm', 'h' => self::Homme,
            'femme', 'female', 'f' => self::Femme,
            'autres', 'autre', 'other', 'o' => self::Autres,
            default => null,
        };
    }
}
