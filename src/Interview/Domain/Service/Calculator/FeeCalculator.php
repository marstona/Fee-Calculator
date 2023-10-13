<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Domain\Service\Calculator;

use Money\Money;
use PragmaGoTech\Interview\Domain\ValueObject\LoanProposal;

interface FeeCalculator
{
    /**
     * @param  LoanProposal $application
     * @return Money
     */
    public function calculate(LoanProposal $application): Money;
}
