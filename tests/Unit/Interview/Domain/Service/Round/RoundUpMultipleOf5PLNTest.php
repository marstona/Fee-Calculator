<?php

declare(strict_types=1);

namespace PragmaGoTech\Tests\Unit\Interview\Domain\Service\Round;

use Codeception\Test\Unit;
use Money\Money;
use PragmaGoTech\Interview\Domain\Service\Round\RoundUpMultipleOf5PLN;

class RoundUpMultipleOf5PLNTest extends Unit
{
    public function testRoundingUpToNearestMultipleOf5WhenAlreadyMultipleOf5ShouldNotChangeValue()
    {
        $roundUpService = new RoundUpMultipleOf5PLN();

        $amount1 = Money::PLN(1000); // Should remain 1000 (already a multiple of 5 PLN)
        $amount2 = Money::PLN(2500); // Should remain 2500 (already a multiple of 5 PLN)

        $roundedAmount1 = $roundUpService->round($amount1);
        $roundedAmount2 = $roundUpService->round($amount2);

        $this->assertEquals(Money::PLN(1000), $roundedAmount1);
        $this->assertEquals(Money::PLN(2500), $roundedAmount2);
    }

    public function testRoundingUpToNearestMultipleOf5WithOddValueShouldRoundUp()
    {
        $roundUpService = new RoundUpMultipleOf5PLN();

        $amount1 = Money::PLN(1349); // Should round up to 15 PLN
        $amount2 = Money::PLN(3501); // Should round up to 40 PLN

        $roundedAmount1 = $roundUpService->round($amount1);
        $roundedAmount2 = $roundUpService->round($amount2);

        $this->assertEquals(Money::PLN(1500), $roundedAmount1);
        $this->assertEquals(Money::PLN(4000), $roundedAmount2);
    }
}
