<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Domain\Service\Interpolation;

use Money\Money;
use PragmaGoTech\Interview\Domain\ValueObject\Breakpoint;

interface Interpolation
{
    /**
     * @param  Money      $loanAmount
     * @param  Breakpoint $lowerBreakpoint
     * @param  Breakpoint $upperBreakpoint
     * @return Money
     */
    public function interpolate(Money $loanAmount, Breakpoint $lowerBreakpoint, Breakpoint $upperBreakpoint): Money;
}
