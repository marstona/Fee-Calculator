<?php

declare(strict_types=1);

namespace PragmaGoTech\Tests\Unit\Interview\Domain\ValueObject;

use Codeception\Test\Unit;
use PragmaGoTech\Interview\Domain\ValueObject\LoanTerm;
use ValueError;

class LoanTermTest extends Unit
{
    public function testConvertingTermToMonths()
    {
        $term12 = LoanTerm::TERM_12;
        $term24 = LoanTerm::TERM_24;

        $this->assertEquals(12, $term12->toMonths());
        $this->assertEquals(24, $term24->toMonths());
    }

    public function testCreatingTermFromMonths()
    {
        $term12 = LoanTerm::from(12);
        $term24 = LoanTerm::from(24);

        $this->assertEquals(LoanTerm::TERM_12, $term12);
        $this->assertEquals(LoanTerm::TERM_24, $term24);
    }

    public function testInvalidValueThrowsException()
    {
        $this->expectException(ValueError::class);
        LoanTerm::from(10);
    }

    public function testZeroValueThrowsException()
    {
        $this->expectException(ValueError::class);
        LoanTerm::from(0);
    }

    public function testNegativeValueThrowsException()
    {
        $this->expectException(ValueError::class);
        LoanTerm::from(-1);
    }

    public function testRetrievingAllCases()
    {
        $allCases = LoanTerm::cases();

        $this->assertCount(2, $allCases);
        $this->assertEquals(LoanTerm::TERM_12, $allCases[0]);
        $this->assertEquals(LoanTerm::TERM_24, $allCases[1]);
    }
}
