<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Application\Repository;

use Money\Money;
use PragmaGoTech\Interview\Domain\Exception\Repository\NoResultException;
use PragmaGoTech\Interview\Domain\Repository\BreakpointRepository;
use PragmaGoTech\Interview\Domain\Service\Storage\BreakpointsStorage;
use PragmaGoTech\Interview\Domain\ValueObject\Breakpoint;
use PragmaGoTech\Interview\Domain\ValueObject\LoanTerm;

final readonly class FixedBreakpointRepository implements BreakpointRepository
{
    /**
     * @param BreakpointsStorage $storage
     */
    public function __construct(
        private BreakpointsStorage $storage
    ) {
    }

    /**
     * @param  LoanTerm          $term
     * @param  Money             $amount
     * @return Breakpoint[]
     * @throws NoResultException
     */
    public function findBreakpoints(LoanTerm $term, Money $amount): array
    {
        $breakpoints = $this->storage->getByTerm($term);
        $breakpointsPair = $this->findBreakpointPair($breakpoints, $amount);

        if (! isset($breakpointsPair[0], $breakpointsPair[1])) {
            throw new NoResultException('No breakpoints were found.');
        }

        return $breakpointsPair;
    }

    /**
     * @param  Breakpoint[]        $breakpoints
     * @param  Money               $amount
     * @return (Breakpoint|null)[]
     */
    private function findBreakpointPair(array $breakpoints, Money $amount): array
    {
        if (! $this->isAmountInRange($amount, $breakpoints)) {
            return [];
        }

        $lowerBreakpoint = null;
        $upperBreakpoint = null;

        foreach ($breakpoints as $breakpoint) {
            if ($amount->greaterThanOrEqual($breakpoint->getAmount())) {
                $lowerBreakpoint = $breakpoint;
            } else {
                $upperBreakpoint = $breakpoint;
                break;
            }
        }

        if ($upperBreakpoint === null) {
            $elementsCount = count($breakpoints);
            $lowerBreakpoint = $breakpoints[$elementsCount - 2];
            $upperBreakpoint = $breakpoints[$elementsCount - 1];
        }

        return [$lowerBreakpoint, $upperBreakpoint];
    }

    /**
     * @param  Money        $amount
     * @param  Breakpoint[] $breakpoints
     * @return bool
     */
    private function isAmountInRange(Money $amount, array $breakpoints): bool
    {
        $firstBreakpointAmount = current($breakpoints)->getAmount();
        $lastBreakpointAmount = end($breakpoints)->getAmount();

        return $amount->greaterThanOrEqual($firstBreakpointAmount) &&
            $amount->lessThanOrEqual($lastBreakpointAmount);
    }
}
