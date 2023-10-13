<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Domain\Service\Interpolation;

use Money\Money;
use PragmaGoTech\Interview\Domain\ValueObject\Breakpoint;

class LinearInterpolation implements Interpolation
{
    /**
     * @param  Money      $loanAmount
     * @param  Breakpoint $lowerBreakpoint
     * @param  Breakpoint $upperBreakpoint
     * @return Money
     */
    public function interpolate(Money $loanAmount, Breakpoint $lowerBreakpoint, Breakpoint $upperBreakpoint): Money
    {
        // Calculate the differences
        $amountDifference = $upperBreakpoint->getAmount()->subtract($lowerBreakpoint->getAmount());
        $amountDifferenceInt = $amountDifference->getAmount() / 100;

        $feeDifference = $upperBreakpoint->getFee()->subtract($lowerBreakpoint->getFee());
        $feeDifferenceInt = $feeDifference->getAmount() / 100;

        // Calculate the proportion
        $loanAmountDifference = $loanAmount->subtract($lowerBreakpoint->getAmount());
        $proportion = $loanAmountDifference->divide($amountDifferenceInt);

        // Calculate the interpolated fee
        $interpolatedFee = $proportion->multiply($feeDifferenceInt)->add($lowerBreakpoint->getFee());

        return $interpolatedFee;
    }
}
