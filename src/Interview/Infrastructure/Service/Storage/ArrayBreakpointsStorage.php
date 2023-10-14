<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Infrastructure\Service\Storage;

use PragmaGoTech\Interview\Application\Factory\PLNMoneyFactory;
use PragmaGoTech\Interview\Domain\Exception\Money\AmountPrecisionException;
use PragmaGoTech\Interview\Domain\Exception\Storage\NoDataException;
use PragmaGoTech\Interview\Domain\Factory\MoneyFactory;
use PragmaGoTech\Interview\Domain\Service\Storage\BreakpointsStorage;
use PragmaGoTech\Interview\Domain\ValueObject\Breakpoint;
use PragmaGoTech\Interview\Domain\ValueObject\LoanTerm;

class ArrayBreakpointsStorage implements BreakpointsStorage
{
    private const FEE_STRUCTURE_PLN = [
        12 => [
            '1000' => '50',
            '2000' => '90',
            '3000' => '90',
            '4000' => '115',
            '5000' => '100',
            '6000' => '120',
            '7000' => '140',
            '8000' => '160',
            '9000' => '180',
            '10000' => '200',
            '11000' => '220',
            '12000' => '240',
            '13000' => '260',
            '14000' => '280',
            '15000' => '300',
            '16000' => '320',
            '17000' => '340',
            '18000' => '360',
            '19000' => '380',
            '20000' => '400',
        ],
        24 => [
            '1000' => '70',
            '2000' => '100',
            '3000' => '120',
            '4000' => '160',
            '5000' => '200',
            '6000' => '240',
            '7000' => '280',
            '8000' => '320',
            '9000' => '360',
            '10000' => '400',
            '11000' => '440',
            '12000' => '480',
            '13000' => '520',
            '14000' => '560',
            '15000' => '600',
            '16000' => '640',
            '17000' => '680',
            '18000' => '720',
            '19000' => '760',
            '20000' => '800',
        ],
    ];

    /**
     * @param PLNMoneyFactory $moneyFactory
     */
    public function __construct(
        private readonly MoneyFactory $moneyFactory
    ) {
    }

    /**
     * @param  LoanTerm                 $term
     * @return Breakpoint[]
     * @throws NoDataException
     * @throws AmountPrecisionException
     */
    public function getByTerm(LoanTerm $term): array
    {
        $termValue = $term->toMonths();

        if (! array_key_exists($termValue, self::FEE_STRUCTURE_PLN)) {
            throw new NoDataException('No breakpoints in storage.');
        }

        $breakpoints = [];
        foreach (self::FEE_STRUCTURE_PLN[$termValue] as $amountPln => $feePln) {
            $amount = $this->moneyFactory->createMoney((string) $amountPln);
            $fee = $this->moneyFactory->createMoney($feePln);

            $breakpoints[] = new Breakpoint($amount, $fee);
        }

        if (count($breakpoints) < 1) {
            throw new NoDataException('No breakpoints in storage.');
        }

        return $breakpoints;
    }
}
