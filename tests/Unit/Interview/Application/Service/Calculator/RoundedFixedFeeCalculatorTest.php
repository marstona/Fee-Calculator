<?php

declare(strict_types=1);

namespace PragmaGoTech\Tests\Unit\Interview\Application\Service\Calculator;

use Codeception\Test\Unit;
use Money\Money;
use PragmaGoTech\Interview\Application\Factory\PLNMoneyFactory;
use PragmaGoTech\Interview\Application\Repository\FixedBreakpointRepository;
use PragmaGoTech\Interview\Application\Service\Calculator\FixedFeeCalculator;
use PragmaGoTech\Interview\Application\Service\Calculator\RoundedFixedFeeCalculator;
use PragmaGoTech\Interview\Domain\Exception\Loan\LoanAmountException;
use PragmaGoTech\Interview\Domain\Service\Interpolation\LinearInterpolation;
use PragmaGoTech\Interview\Domain\Service\Round\RoundUpMultipleOf5PLN;
use PragmaGoTech\Interview\Domain\ValueObject\LoanProposal;
use PragmaGoTech\Interview\Domain\ValueObject\LoanTerm;
use PragmaGoTech\Interview\Infrastructure\Service\Storage\ArrayBreakpointsStorage;

class RoundedFixedFeeCalculatorTest extends Unit
{
    private RoundedFixedFeeCalculator $calculator;

    protected function _before()
    {
        $moneyFactory = new PLNMoneyFactory();
        $storage = new ArrayBreakpointsStorage($moneyFactory);
        $repository = new FixedBreakpointRepository($storage);
        $interpolation = new LinearInterpolation();
        $fixeCalculator = new FixedFeeCalculator($repository, $interpolation);
        $round = new RoundUpMultipleOf5PLN($moneyFactory);
        $this->calculator = new RoundedFixedFeeCalculator($fixeCalculator, $round);
    }

    public function testCalculateFeeFor12MonthsTermWithValidAmount()
    {
        // Arrange
        $term = LoanTerm::TERM_12;
        $amount = Money::PLN(1925000);
        $expectedFee = Money::PLN(38500);

        // Act
        $result = $this->calculator->calculate(new LoanProposal($term, $amount));

        // Assert
        $this->assertEquals($expectedFee, $result);
    }

    public function testCalculateFeeFor12MonthsTermWithValidFloatingPointAmountSlightlyAbove1000PLN()
    {
        // Arrange
        $term = LoanTerm::TERM_12;
        $amount = Money::PLN(100001);
        $expectedFee = Money::PLN(5499);

        // Act
        $result = $this->calculator->calculate(new LoanProposal($term, $amount));

        // Assert
        $this->assertEquals($expectedFee, $result);
    }

    public function testCalculateFeeFor12MonthsTermWithValidFloatingPointAmount()
    {
        // Arrange
        $term = LoanTerm::TERM_12;
        $amount = Money::PLN(1111169);
        $expectedFee = Money::PLN(22331);

        // Act
        $result = $this->calculator->calculate(new LoanProposal($term, $amount));

        // Assert
        $this->assertEquals($expectedFee, $result);
    }

    public function testCalculateFeeFor12MonthsTermWithValidFloatingPointAmountSlightlyBelow20000PLN()
    {
        // Arrange
        $term = LoanTerm::TERM_12;
        $amount = Money::PLN(1999999);
        $expectedFee = Money::PLN(40001);

        // Act
        $result = $this->calculator->calculate(new LoanProposal($term, $amount));

        // Assert
        $this->assertEquals($expectedFee, $result);
    }

    public function testCalculateFeeFor12MonthsTermWithZeroAmount()
    {
        $term = LoanTerm::TERM_12;
        $amount = Money::PLN(0);

        $this->expectException(LoanAmountException::class);
        $this->calculator->calculate(new LoanProposal($term, $amount));
    }

    public function testCalculateFeeFor12MonthsTermWithInvalidAmount()
    {
        $term = LoanTerm::TERM_12;
        $amount = Money::PLN(-100000);

        $this->expectException(LoanAmountException::class);
        $this->calculator->calculate(new LoanProposal($term, $amount));
    }

    public function testCalculateFeeFor24MonthsTermWithValidAmount()
    {
        // Arrange
        $term = LoanTerm::TERM_24;
        $amount = Money::PLN(1150000);
        $expectedFee = Money::PLN(46000);

        // Act
        $result = $this->calculator->calculate(new LoanProposal($term, $amount));

        // Assert
        $this->assertEquals($expectedFee, $result);
    }

    public function testCalculateFeeFor24MonthsTermWithValidFloatingPointAmountSlightlyAbove1000PLN()
    {
        // Arrange
        $term = LoanTerm::TERM_24;
        $amount = Money::PLN(100001);
        $expectedFee = Money::PLN(7499);

        // Act
        $result = $this->calculator->calculate(new LoanProposal($term, $amount));

        // Assert
        $this->assertEquals($expectedFee, $result);
    }

    public function testCalculateFeeFor24MonthsTermWithValidFloatingPointAmount()
    {
        // Arrange
        $term = LoanTerm::TERM_24;
        $amount = Money::PLN(999901);
        $expectedFee = Money::PLN(40099);

        // Act
        $result = $this->calculator->calculate(new LoanProposal($term, $amount));

        // Assert
        $this->assertEquals($expectedFee, $result);
    }

    public function testCalculateFeeFor24MonthsTermWithValidFloatingPointAmountSlightlyBelow20000PLN()
    {
        // Arrange
        $term = LoanTerm::TERM_24;
        $amount = Money::PLN(1999999);
        $expectedFee = Money::PLN(80001);

        // Act
        $result = $this->calculator->calculate(new LoanProposal($term, $amount));

        // Assert
        $this->assertEquals($expectedFee, $result);
    }

    public function testCalculateFeeFor24MonthsTermWithZeroAmount()
    {
        $term = LoanTerm::TERM_24;
        $amount = Money::PLN(0);

        $this->expectException(LoanAmountException::class);
        $this->calculator->calculate(new LoanProposal($term, $amount));
    }

    public function testCalculateFeeFor24MonthsTermWithInvalidAmount()
    {
        $term = LoanTerm::TERM_24;
        $amount = Money::PLN(-100000);

        $this->expectException(LoanAmountException::class);
        $this->calculator->calculate(new LoanProposal($term, $amount));
    }
}
