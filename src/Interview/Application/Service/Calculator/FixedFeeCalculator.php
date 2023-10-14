<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Application\Service\Calculator;

use Money\Money;
use PragmaGoTech\Interview\Domain\Repository\BreakpointRepository;
use PragmaGoTech\Interview\Domain\Service\Calculator\FeeCalculator;
use PragmaGoTech\Interview\Domain\Service\Interpolation\Interpolation;
use PragmaGoTech\Interview\Domain\ValueObject\LoanProposal;

final readonly class FixedFeeCalculator implements FeeCalculator
{
    /**
     * @param BreakpointRepository $breakpointRepository
     * @param Interpolation        $interpolation
     */
    public function __construct(
        private BreakpointRepository $breakpointRepository,
        private Interpolation $interpolation
    ) {
    }

    /**
     * @param  LoanProposal $application
     * @return Money
     */
    public function calculate(LoanProposal $application): Money
    {
        $term = $application->term();
        $amount = $application->amount();
        [$lowerBreakpoint, $upperBreakpoint] = $this->breakpointRepository->findBreakpoints($term, $amount);

        return $this->interpolation->interpolate($amount, $lowerBreakpoint, $upperBreakpoint);
    }
}
