<?php

namespace Tests\Unit\Enums;

use App\Enums\ActivityLevel;
use PHPUnit\Framework\TestCase;

class ActivityLevelTest extends TestCase
{
    public function test_canonical_values_are_english(): void
    {
        $this->assertSame('sedentary', ActivityLevel::Sedentary->value);
        $this->assertSame('moderate', ActivityLevel::Moderate->value);
        $this->assertSame('active', ActivityLevel::Active->value);
    }

    public function test_tdee_multipliers(): void
    {
        $this->assertSame(1.2, ActivityLevel::Sedentary->tdeeMultiplier());
        $this->assertSame(1.55, ActivityLevel::Moderate->tdeeMultiplier());
        $this->assertSame(1.725, ActivityLevel::Active->tdeeMultiplier());
    }

    public function test_from_legacy_accepts_canonical_english(): void
    {
        $this->assertSame(ActivityLevel::Sedentary, ActivityLevel::fromLegacy('sedentary'));
        $this->assertSame(ActivityLevel::Moderate, ActivityLevel::fromLegacy('moderate'));
        $this->assertSame(ActivityLevel::Active, ActivityLevel::fromLegacy('active'));
    }

    public function test_from_legacy_accepts_legacy_french(): void
    {
        $this->assertSame(ActivityLevel::Sedentary, ActivityLevel::fromLegacy('Sedentaire'));
        $this->assertSame(ActivityLevel::Sedentary, ActivityLevel::fromLegacy('Sédentaire'));
        $this->assertSame(ActivityLevel::Active, ActivityLevel::fromLegacy('Actif(ve)'));
        $this->assertSame(ActivityLevel::Active, ActivityLevel::fromLegacy('Actif'));
    }

    /**
     * Regression: "Moyennement Actif(ve)" contains "actif" but must map to
     * MODERATE, not ACTIVE (the previous mapActivityLevel() got this wrong).
     */
    public function test_from_legacy_maps_moderately_active_to_moderate(): void
    {
        $this->assertSame(ActivityLevel::Moderate, ActivityLevel::fromLegacy('Moyennement Actif(ve)'));
        $this->assertSame(ActivityLevel::Moderate, ActivityLevel::fromLegacy('Moyennement actif'));
        $this->assertSame(ActivityLevel::Moderate, ActivityLevel::fromLegacy('Modéré'));
    }

    public function test_from_legacy_returns_null_when_unknown_or_empty(): void
    {
        $this->assertNull(ActivityLevel::fromLegacy('inconnu'));
        $this->assertNull(ActivityLevel::fromLegacy(''));
        $this->assertNull(ActivityLevel::fromLegacy(null));
    }

    public function test_labels_are_french(): void
    {
        $this->assertSame('Sédentaire', ActivityLevel::Sedentary->getLabel());
        $this->assertSame('Moyennement actif', ActivityLevel::Moderate->getLabel());
        $this->assertSame('Actif', ActivityLevel::Active->getLabel());
    }
}
