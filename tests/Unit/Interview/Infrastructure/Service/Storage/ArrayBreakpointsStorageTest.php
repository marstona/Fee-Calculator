<?php

declare(strict_types=1);

namespace PragmaGoTech\Tests\Unit\Interview\Infrastructure\Service\Storage;

use Codeception\Test\Unit;
use Money\Money;
use PragmaGoTech\Interview\Application\Factory\PLNMoneyFactory;
use PragmaGoTech\Interview\Domain\Service\Storage\BreakpointsStorage;
use PragmaGoTech\Interview\Domain\ValueObject\Breakpoint;
use PragmaGoTech\Interview\Domain\ValueObject\LoanTerm;
use PragmaGoTech\Interview\Infrastructure\Service\Storage\ArrayBreakpointsStorage;

class ArrayBreakpointsStorageTest extends Unit
{
    private BreakpointsStorage $storage;

    protected function _before()
    {
        $moneyFactory = new PLNMoneyFactory();
        $this->storage = new ArrayBreakpointsStorage($moneyFactory);
    }

    public function testShouldRetrieveBreakpointsFor12MonthsTerm()
    {
        $validTerm = LoanTerm::TERM_12;
        $breakpoints = $this->storage->getByTerm($validTerm);

        $this->assertIsArray($breakpoints);
        $this->assertGreaterThanOrEqual(2, $breakpoints);
        $this->assertInstanceOf(Breakpoint::class, $breakpoints[0]);

        $this->assertEquals(Money::PLN(100000), current($breakpoints)->getAmount());
        $this->assertEquals(Money::PLN(5000), current($breakpoints)->getFee());

        $this->assertEquals(Money::PLN(2000000), end($breakpoints)->getAmount());
        $this->assertEquals(Money::PLN(40000), end($breakpoints)->getFee());
    }

    public function testShouldRetrieveBreakpointsFor24MonthsTerm()
    {
        $validTerm = LoanTerm::TERM_24;
        $breakpoints = $this->storage->getByTerm($validTerm);

        $this->assertIsArray($breakpoints);
        $this->assertGreaterThanOrEqual(2, $breakpoints);
        $this->assertInstanceOf(Breakpoint::class, $breakpoints[0]);

        $this->assertEquals(Money::PLN(100000), current($breakpoints)->getAmount());
        $this->assertEquals(Money::PLN(7000), current($breakpoints)->getFee());

        $this->assertEquals(Money::PLN(2000000), end($breakpoints)->getAmount());
        $this->assertEquals(Money::PLN(80000), end($breakpoints)->getFee());
    }
}
