<?php

declare(strict_types=1);

namespace PragmaGoTech\Tests\Unit\Interview\Application\Repository;

use Codeception\Test\Unit;
use Money\Money;
use PragmaGoTech\Interview\Application\Repository\FixedBreakpointRepository;
use PragmaGoTech\Interview\Domain\Exception\Repository\NoResultException;
use PragmaGoTech\Interview\Domain\Service\Storage\BreakpointsStorage;
use PragmaGoTech\Interview\Domain\ValueObject\Breakpoint;
use PragmaGoTech\Interview\Domain\ValueObject\LoanTerm;

class FixedBreakpointRepositoryTest extends Unit
{
    private BreakpointsStorage $storage;

    protected function _before()
    {
        $storage = $this->createMock(BreakpointsStorage::class);
        $storage->method('getByTerm')->willReturn([
            new Breakpoint(Money::PLN(100000), Money::PLN(5000)),
            new Breakpoint(Money::PLN(200000), Money::PLN(9000)),
            new Breakpoint(Money::PLN(300000), Money::PLN(9000)),
            new Breakpoint(Money::PLN(400000), Money::PLN(115000)),
        ]);
        $this->storage = $storage;
    }

    public function testFindBreakpointsWithValidAmount()
    {
        $repository = new FixedBreakpointRepository($this->storage);
        $term = LoanTerm::TERM_12;
        $amount = Money::PLN(321321);

        $breakpoints = $repository->findBreakpoints($term, $amount);

        $this->assertIsArray($breakpoints);
        $this->assertCount(2, $breakpoints);
        $this->assertInstanceOf(Breakpoint::class, $breakpoints[0]);
        $this->assertInstanceOf(Breakpoint::class, $breakpoints[1]);
        $this->assertEquals(Money::PLN(300000), $breakpoints[0]->getAmount());
        $this->assertEquals(Money::PLN(9000), $breakpoints[0]->getFee());
        $this->assertEquals(Money::PLN(400000), $breakpoints[1]->getAmount());
        $this->assertEquals(Money::PLN(115000), $breakpoints[1]->getFee());
    }

    public function testFindBreakpointsWithAmountEqualToFirstBreakpoint()
    {
        $repository = new FixedBreakpointRepository($this->storage);
        $term = LoanTerm::TERM_12;
        $amount = Money::PLN(100000);

        $breakpoints = $repository->findBreakpoints($term, $amount);

        $this->assertIsArray($breakpoints);
        $this->assertCount(2, $breakpoints);
        $this->assertInstanceOf(Breakpoint::class, $breakpoints[0]);
        $this->assertInstanceOf(Breakpoint::class, $breakpoints[1]);
        $this->assertEquals(Money::PLN(100000), $breakpoints[0]->getAmount());
        $this->assertEquals(Money::PLN(5000), $breakpoints[0]->getFee());
        $this->assertEquals(Money::PLN(200000), $breakpoints[1]->getAmount());
        $this->assertEquals(Money::PLN(9000), $breakpoints[1]->getFee());
    }

    public function testFindBreakpointsWithAmountEqualToLastBreakpoint()
    {
        $repository = new FixedBreakpointRepository($this->storage);
        $term = LoanTerm::TERM_12;
        $amount = Money::PLN(400000);

        $breakpoints = $repository->findBreakpoints($term, $amount);

        $this->assertIsArray($breakpoints);
        $this->assertCount(2, $breakpoints);
        $this->assertInstanceOf(Breakpoint::class, $breakpoints[0]);
        $this->assertInstanceOf(Breakpoint::class, $breakpoints[1]);
        $this->assertEquals(Money::PLN(300000), $breakpoints[0]->getAmount());
        $this->assertEquals(Money::PLN(9000), $breakpoints[0]->getFee());
        $this->assertEquals(Money::PLN(400000), $breakpoints[1]->getAmount());
        $this->assertEquals(Money::PLN(115000), $breakpoints[1]->getFee());
    }

    public function testFindBreakpointsWithAmountLowerThanFirstBreakpoint()
    {
        $repository = new FixedBreakpointRepository($this->storage);
        $term = LoanTerm::TERM_12;
        $amount = Money::PLN(99999);

        $this->expectException(NoResultException::class);
        $repository->findBreakpoints($term, $amount);
    }

    public function testFindBreakpointsWithAmountGreaterThanLastBreakpoint()
    {
        $repository = new FixedBreakpointRepository($this->storage);
        $term = LoanTerm::TERM_12;
        $amount = Money::PLN(400001);

        $this->expectException(NoResultException::class);
        $repository->findBreakpoints($term, $amount);
    }
}
