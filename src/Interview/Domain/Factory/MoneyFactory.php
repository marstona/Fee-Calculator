<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Domain\Factory;

use Money\Money;

interface MoneyFactory
{
    /**
     * @param  string $amount
     * @return Money
     */
    public function createMoney(string $amount): Money;
}
