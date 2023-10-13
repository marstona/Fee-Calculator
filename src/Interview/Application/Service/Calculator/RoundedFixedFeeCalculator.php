<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Application\Service\Calculator;

use Money\Money;
use PragmaGoTech\Interview\Domain\Service\Calculator\FeeCalculator;
use PragmaGoTech\Interview\Domain\Service\Round\Round;
use PragmaGoTech\Interview\Domain\ValueObject\LoanProposal;

readonly class RoundedFixedFeeCalculator implements FeeCalculator
{
    /**
     * @param FixedFeeCalculator $feeCalculator
     * @param Round              $round
     */
    public function __construct(
        private FixedFeeCalculator $feeCalculator,
        private Round $round
    ) {
    }

    /**
     * @param  LoanProposal $application
     * @return Money
     */
    public function calculate(LoanProposal $application): Money
    {
        $feeAmount = $this->feeCalculator->calculate($application);
        $loanAmount = $application->amount();

        // Ensure that fee + loan amount is an exact multiple of 5
        $totalAmount = $loanAmount->add($feeAmount);
        $roundedTotalAmount = $this->round->round($totalAmount);

        // Calculate and return the final fee
        return $roundedTotalAmount->subtract($loanAmount);
    }
}
