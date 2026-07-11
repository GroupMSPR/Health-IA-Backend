<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

/**
 * Tolerant enum cast. Normalizes legacy/mixed stored values through the
 * enum's fromLegacy() parser on both read and write, so pre-existing dirty
 * data (EN gender, FR activity, odd casing) never throws on hydration.
 *
 * Usage: 'gender' => LegacyEnum::class.':'.Gender::class
 *
 * The target enum must expose a static fromLegacy(?string): ?self method.
 *
 * @implements CastsAttributes<\BackedEnum|null, \BackedEnum|string|null>
 */
class LegacyEnum implements CastsAttributes
{
    /**
     * @param  class-string  $enumClass
     */
    public function __construct(protected string $enumClass) {}

    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if ($value === null) {
            return null;
        }

        return $this->enumClass::fromLegacy((string) $value);
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof $this->enumClass) {
            return $value->value;
        }

        $enum = $this->enumClass::fromLegacy((string) $value);

        // Keep the raw value when unrecognized so validation can reject it
        // instead of silently nulling the column.
        return $enum?->value ?? $value;
    }
}
