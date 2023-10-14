<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Domain\Service\Interpolation;

use Money\Money;
use PragmaGoTech\Interview\Domain\ValueObject\Breakpoint;

class LinearInterpolation implements Interpolation
{
    /**
     * Interpolate the fee based on loan amount between two breakpoints.
     *
     * @param  Money      $loanAmount
     * @param  Breakpoint $lowerBreakpoint
     * @param  Breakpoint $upperBreakpoint
     * @return Money
     */
    public function interpolate(Money $loanAmount, Breakpoint $lowerBreakpoint, Breakpoint $upperBreakpoint): Money
    {
        // Extract the amounts and fees as integers
        $loanAmountInt = $loanAmount->getAmount();
        $lowerAmountInt = $lowerBreakpoint->getAmount()->getAmount();
        $upperAmountInt = $upperBreakpoint->getAmount()->getAmount();
        $lowerFeeInt = $lowerBreakpoint->getFee()->getAmount();
        $upperFeeInt = $upperBreakpoint->getFee()->getAmount();

        // Calculate the differences
        $amountDifference = $upperAmountInt - $lowerAmountInt;
        $feeDifference = $upperFeeInt - $lowerFeeInt;

        // Calculate the proportion
        $proportion = ($loanAmountInt - $lowerAmountInt) / $amountDifference;

        // Calculate the interpolated fee
        $interpolatedFeeInt = (int) ceil($proportion * $feeDifference + $lowerFeeInt);

        // Create a Money object with the calculated fee
        return Money::PLN($interpolatedFeeInt);
    }
}
