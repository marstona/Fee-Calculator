<?php

declare(strict_types=1);

namespace PragmaGoTech\Tests\Unit\Interview\Application\Service\Calculator;

use Codeception\Test\Unit;
use Money\Money;
use PragmaGoTech\Interview\Application\Factory\PLNMoneyFactory;
use PragmaGoTech\Interview\Application\Repository\FixedBreakpointRepository;
use PragmaGoTech\Interview\Application\Service\Calculator\FixedFeeCalculator;
use PragmaGoTech\Interview\Domain\Exception\Loan\LoanAmountException;
use PragmaGoTech\Interview\Domain\Service\Interpolation\LinearInterpolation;
use PragmaGoTech\Interview\Domain\ValueObject\LoanProposal;
use PragmaGoTech\Interview\Domain\ValueObject\LoanTerm;
use PragmaGoTech\Interview\Infrastructure\Service\Storage\ArrayBreakpointsStorage;

class FixedFeeCalculatorTest extends Unit
{
    private FixedFeeCalculator $calculator;

    protected function _before()
    {
        $moneyFactory = new PLNMoneyFactory();
        $storage = new ArrayBreakpointsStorage($moneyFactory);
        $repository = new FixedBreakpointRepository($storage);
        $interpolation = new LinearInterpolation();
        $this->calculator = new FixedFeeCalculator($repository, $interpolation);
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

    public function testCalculateFeeFor12MonthsTermWithValidFloatingPointAmount()
    {
        // Arrange
        $term = LoanTerm::TERM_12;
        $amount = Money::PLN(1111169);
        $expectedFee = Money::PLN(22224);

        // Act
        $result = $this->calculator->calculate(new LoanProposal($term, $amount));

        // Assert
        $this->assertEquals($expectedFee, $result);
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

    public function testCalculateFeeFor24MonthsTermWithValidFloatingPointAmount()
    {
        // Arrange
        $term = LoanTerm::TERM_24;
        $amount = Money::PLN(999901);
        $expectedFee = Money::PLN(39997);

        // Act
        $result = $this->calculator->calculate(new LoanProposal($term, $amount));

        // Assert
        $this->assertEquals($expectedFee, $result);
    }

    public function testCalculateFeeFor12MonthsTermWithAmountLowerThanLowestBreakpoint()
    {
        // Arrange
        $term = LoanTerm::TERM_12;
        $amount = Money::PLN(99999); // An amount below the lowest breakpoint.

        // Act and Assert
        $this->expectException(LoanAmountException::class);
        $this->calculator->calculate(new LoanProposal($term, $amount));
    }

    public function testCalculateFeeFor12MonthsTermWithAmountUpperThanHighestBreakpoint()
    {
        // Arrange
        $term = LoanTerm::TERM_12;
        $amount = Money::PLN(2000001); // An amount above the highest breakpoint.

        // Act and Assert
        $this->expectException(LoanAmountException::class);
        $this->calculator->calculate(new LoanProposal($term, $amount));
    }

    public function testCalculateFeeFor24MonthsTermWithAmountLowerThanLowestBreakpoint()
    {
        // Arrange
        $term = LoanTerm::TERM_24;
        $amount = Money::PLN(99999); // An amount below the lowest breakpoint.

        // Act and Assert
        $this->expectException(LoanAmountException::class);
        $this->calculator->calculate(new LoanProposal($term, $amount));
    }

    public function testCalculateFeeFor24MonthsTermWithAmountUpperThanHighestBreakpoint()
    {
        // Arrange
        $term = LoanTerm::TERM_24;
        $amount = Money::PLN(2000001); // An amount above the highest breakpoint.

        // Act and Assert
        $this->expectException(LoanAmountException::class);
        $this->calculator->calculate(new LoanProposal($term, $amount));
    }
}
