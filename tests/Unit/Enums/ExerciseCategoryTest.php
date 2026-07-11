<?php

namespace Tests\Unit\Enums;

use App\Enums\ExerciseCategory;
use PHPUnit\Framework\TestCase;

class ExerciseCategoryTest extends TestCase
{
    public function test_canonical_values_are_french(): void
    {
        $this->assertSame('Musculation', ExerciseCategory::Musculation->value);
        $this->assertSame('Cardio', ExerciseCategory::Cardio->value);
        $this->assertSame('Poids du corps', ExerciseCategory::PoidsDuCorps->value);
    }

    public function test_from_legacy_normalizes_casing(): void
    {
        // Factory previously stored "Poids du Corps" (capital C).
        $this->assertSame(ExerciseCategory::PoidsDuCorps, ExerciseCategory::fromLegacy('Poids du Corps'));
        $this->assertSame(ExerciseCategory::PoidsDuCorps, ExerciseCategory::fromLegacy('poids du corps'));
        $this->assertSame(ExerciseCategory::Cardio, ExerciseCategory::fromLegacy('cardio'));
        $this->assertSame(ExerciseCategory::Musculation, ExerciseCategory::fromLegacy('MUSCULATION'));
    }

    public function test_from_legacy_returns_null_when_unknown_or_empty(): void
    {
        $this->assertNull(ExerciseCategory::fromLegacy('yoga'));
        $this->assertNull(ExerciseCategory::fromLegacy(''));
        $this->assertNull(ExerciseCategory::fromLegacy(null));
    }
}
