<?php

declare(strict_types=1);

namespace PragmaGoTech\Tests\Unit\Interview\Domain\ValueObject;

use Codeception\Test\Unit;
use Money\Money;
use PragmaGoTech\Interview\Domain\Exception\Loan\LoanAmountException;
use PragmaGoTech\Interview\Domain\ValueObject\LoanProposal;
use PragmaGoTech\Interview\Domain\ValueObject\LoanTerm;

class LoanProposalTest extends Unit
{
    public function testCreatingValidLoanProposal()
    {
        $term = LoanTerm::TERM_12;
        $amount = Money::PLN(150069);

        $loanProposal = new LoanProposal($term, $amount);

        $this->assertInstanceOf(LoanProposal::class, $loanProposal);
    }

    public function testCreatingLoanProposalWithAmountTooLowThrowsException()
    {
        $this->expectException(LoanAmountException::class);
        $term = LoanTerm::TERM_24;
        $amount = Money::PLN(99999);

        new LoanProposal($term, $amount);
    }

    public function testCreatingLoanProposalWithAmountTooHighThrowsException()
    {
        $this->expectException(LoanAmountException::class);
        $term = LoanTerm::TERM_12;
        $amount = Money::PLN(2000001);

        new LoanProposal($term, $amount);
    }

    public function testCreatingLoanProposalWithAmountAtMinimum()
    {
        $term = LoanTerm::TERM_12;
        $amount = Money::PLN(100000);

        $loanProposal = new LoanProposal($term, $amount);

        $this->assertInstanceOf(LoanProposal::class, $loanProposal);
    }

    public function testCreatingLoanProposalWithAmountAtMaximum()
    {
        $term = LoanTerm::TERM_24;
        $amount = Money::PLN(2000000);

        $loanProposal = new LoanProposal($term, $amount);

        $this->assertInstanceOf(LoanProposal::class, $loanProposal);
    }
}
