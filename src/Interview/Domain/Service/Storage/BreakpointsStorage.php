<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Domain\Service\Storage;

use PragmaGoTech\Interview\Domain\ValueObject\Breakpoint;
use PragmaGoTech\Interview\Domain\ValueObject\LoanTerm;

interface BreakpointsStorage
{
    /**
     * @param  LoanTerm     $term
     * @return Breakpoint[]
     */
    public function getByTerm(LoanTerm $term): array;
}
