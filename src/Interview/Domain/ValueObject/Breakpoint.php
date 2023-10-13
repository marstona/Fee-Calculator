<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Domain\ValueObject;

use Money\Money;

readonly class Breakpoint
{
    /**
     * @param Money $amount
     * @param Money $fee
     */
    public function __construct(
        private Money $amount,
        private Money $fee
    ) {
    }

    /**
     * @return Money
     */
    public function getAmount(): Money
    {
        return $this->amount;
    }

    /**
     * @return Money
     */
    public function getFee(): Money
    {
        return $this->fee;
    }
}
