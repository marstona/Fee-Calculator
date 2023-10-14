<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Domain\Service\Round;

use Money\Money;

interface Round
{
    /**
     * @param  Money $amount
     * @return Money
     */
    public function round(Money $amount): Money;
}
