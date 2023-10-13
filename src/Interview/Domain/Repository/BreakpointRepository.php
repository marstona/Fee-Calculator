<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Domain\Repository;

use Money\Money;
use PragmaGoTech\Interview\Domain\ValueObject\Breakpoint;
use PragmaGoTech\Interview\Domain\ValueObject\LoanTerm;

interface BreakpointRepository
{
    /**
     * @param  LoanTerm     $term
     * @param  Money        $amount
     * @return Breakpoint[]
     */
    public function findBreakpoints(LoanTerm $term, Money $amount): array;
}
