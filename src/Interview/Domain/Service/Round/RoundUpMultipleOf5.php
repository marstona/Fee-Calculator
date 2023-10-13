<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Domain\Service\Round;

use Money\Money;
use PragmaGoTech\Interview\Domain\Factory\MoneyFactory;

class RoundUpMultipleOf5 implements Round
{
    private const MULTIPLE = '5';

    /**
     * @param MoneyFactory $moneyFactory
     */
    public function __construct(
        private readonly MoneyFactory $moneyFactory
    ) {
    }

    /**
     * @param  Money $value
     * @return Money
     */
    public function round(Money $value): Money
    {
        // Calculate the nearest multiple of 5 greater than or equal to the value
        $multiple = $this->moneyFactory->createMoney(self::MULTIPLE);
        $remainder = $value->mod($multiple);
        $roundUpAmount = $multiple->subtract($remainder);

        // Ensure the result is a multiple of 5
        $roundUpAmount = $roundUpAmount->mod($multiple);

        // Return the rounded value
        return $value->add($roundUpAmount);
    }
}
