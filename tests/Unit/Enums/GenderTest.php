<?php

namespace Tests\Unit\Enums;

use App\Enums\Gender;
use PHPUnit\Framework\TestCase;

class GenderTest extends TestCase
{
    public function test_canonical_values_are_french(): void
    {
        $this->assertSame('Homme', Gender::Homme->value);
        $this->assertSame('Femme', Gender::Femme->value);
        $this->assertSame('Autres', Gender::Autres->value);
    }

    public function test_from_legacy_accepts_canonical_french(): void
    {
        $this->assertSame(Gender::Homme, Gender::fromLegacy('Homme'));
        $this->assertSame(Gender::Femme, Gender::fromLegacy('Femme'));
        $this->assertSame(Gender::Autres, Gender::fromLegacy('Autres'));
    }

    public function test_from_legacy_accepts_english_variants(): void
    {
        $this->assertSame(Gender::Homme, Gender::fromLegacy('male'));
        $this->assertSame(Gender::Femme, Gender::fromLegacy('female'));
        $this->assertSame(Gender::Autres, Gender::fromLegacy('other'));
    }

    public function test_from_legacy_is_case_insensitive(): void
    {
        $this->assertSame(Gender::Homme, Gender::fromLegacy('  HOMME '));
        $this->assertSame(Gender::Femme, Gender::fromLegacy('FeMaLe'));
    }

    public function test_from_legacy_returns_null_when_unknown_or_empty(): void
    {
        $this->assertNull(Gender::fromLegacy('inconnu'));
        $this->assertNull(Gender::fromLegacy(''));
        $this->assertNull(Gender::fromLegacy(null));
    }

    public function test_labels_are_french(): void
    {
        $this->assertSame('Homme', Gender::Homme->getLabel());
        $this->assertSame('Femme', Gender::Femme->getLabel());
        $this->assertSame('Autre', Gender::Autres->getLabel());
    }
}
