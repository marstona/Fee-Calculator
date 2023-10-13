<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Application\Repository;

use Money\Money;
use PragmaGoTech\Interview\Domain\Exception\Repository\NoResultException;
use PragmaGoTech\Interview\Domain\Repository\BreakpointRepository;
use PragmaGoTech\Interview\Domain\Service\Storage\BreakpointsStorage;
use PragmaGoTech\Interview\Domain\ValueObject\Breakpoint;
use PragmaGoTech\Interview\Domain\ValueObject\LoanTerm;

readonly class FixedBreakpointRepository implements BreakpointRepository
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

        if ($lowerBreakpoint === null || $upperBreakpoint === null) {
            throw new NoResultException('No breakpoints were found.');
        }

        return [$lowerBreakpoint, $upperBreakpoint];
    }
}
