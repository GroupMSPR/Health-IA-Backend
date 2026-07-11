<?php

namespace App\Enums;

/**
 * Contract for backed string enums that can normalize legacy/mixed stored
 * values (different languages, casing) into a canonical case.
 *
 * The @property-read declaration documents the backing value that every
 * implementing enum exposes, so it can be read generically (e.g. from a cast).
 *
 * @property-read string $value
 */
interface LegacyNormalizable
{
    public static function fromLegacy(?string $value): ?self;
}
