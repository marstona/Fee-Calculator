<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Domain\ValueObject;

use Money\Money;
use PragmaGoTech\Interview\Domain\Exception\Loan\LoanAmountException;

class LoanProposal
{
    private const MAX_LOAN_AMOUNT_IN_GROSZE = 2000000;

    private const MIN_LOAN_AMOUNT_IN_GROSZE = 100000;

    /**
     * @throws LoanAmountException
     */
    public function __construct(
        private readonly LoanTerm $term,
        readonly Money $amount
    ) {
        $this->validateAmountRange($amount);
    }

    /**
     * @return Money
     */
    public function amount(): Money
    {
        return $this->amount;
    }

    /**
     * @return LoanTerm
     */
    public function term(): LoanTerm
    {
        return $this->term;
    }

    /**
     * @throws LoanAmountException
     */
    private function validateAmountRange(Money $amount): void
    {
        $minLoanAmount = Money::PLN(self::MIN_LOAN_AMOUNT_IN_GROSZE);
        $maxLoanAmount = Money::PLN(self::MAX_LOAN_AMOUNT_IN_GROSZE);

        if ($amount->lessThan($minLoanAmount) || $amount->greaterThan($maxLoanAmount)) {
            throw new LoanAmountException('Loan amount out of range.');
        }
    }
}
