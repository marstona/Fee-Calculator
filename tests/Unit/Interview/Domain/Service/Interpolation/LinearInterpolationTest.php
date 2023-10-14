<?php

declare(strict_types=1);

namespace PragmaGoTech\Tests\Unit\Interview\Domain\Service\Interpolation;

use Codeception\Test\Unit;
use Money\Money;
use PragmaGoTech\Interview\Domain\Service\Interpolation\LinearInterpolation;
use PragmaGoTech\Interview\Domain\ValueObject\Breakpoint;

class LinearInterpolationTest extends Unit
{
    public function testInterpolationWhenLoanAmountIsEqualToLowerBreakpointAmountShouldReturnLowerBreakpointFee()
    {
        $interpolation = new LinearInterpolation();

        $lowerBreakpoint = new Breakpoint(Money::PLN(100000), Money::PLN(5000));
        $upperBreakpoint = new Breakpoint(Money::PLN(200000), Money::PLN(9000));

        $loanAmount = Money::PLN(100000); // Equals the lower breakpoint amount
        $interpolatedFee = $interpolation->interpolate($loanAmount, $lowerBreakpoint, $upperBreakpoint);

        $this->assertEquals(Money::PLN(5000), $interpolatedFee);
    }

    public function testInterpolationWhenLoanAmountIsEqualToUpperBreakpointAmountShouldReturnUpperBreakpointFee()
    {
        $interpolation = new LinearInterpolation();

        $lowerBreakpoint = new Breakpoint(Money::PLN(100000), Money::PLN(5000));
        $upperBreakpoint = new Breakpoint(Money::PLN(200000), Money::PLN(9000));

        $loanAmount = Money::PLN(200000); // Equals the upper breakpoint amount
        $interpolatedFee = $interpolation->interpolate($loanAmount, $lowerBreakpoint, $upperBreakpoint);

        $this->assertEquals(Money::PLN(9000), $interpolatedFee);
    }

    public function testInterpolationWithLoanAmountInBetweenShouldReturnAppropriateFee()
    {
        $interpolation = new LinearInterpolation();

        $lowerBreakpoint = new Breakpoint(Money::PLN(100000), Money::PLN(5000));
        $upperBreakpoint = new Breakpoint(Money::PLN(200000), Money::PLN(9000));

        $loanAmount = Money::PLN(150000); // 1500 PLN
        $interpolatedFee = $interpolation->interpolate($loanAmount, $lowerBreakpoint, $upperBreakpoint);

        $this->assertEquals(Money::PLN(7000), $interpolatedFee);
    }

    public function testInterpolationWithLoanAmountInBetweenWithFloatingPointShouldReturnAppropriateFee()
    {
        $interpolation = new LinearInterpolation();

        $lowerBreakpoint = new Breakpoint(Money::PLN(100000), Money::PLN(5000));
        $upperBreakpoint = new Breakpoint(Money::PLN(200000), Money::PLN(9000));

        $loanAmount = Money::PLN(151169); // 1511.69 PLN
        $interpolatedFee = $interpolation->interpolate($loanAmount, $lowerBreakpoint, $upperBreakpoint);

        $this->assertEquals(Money::PLN(7047), $interpolatedFee);
    }
}
