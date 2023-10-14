<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Domain\Service\Round;

use Money\Money;

class RoundUpMultipleOf5PLN implements Round
{
    private const ROUND_GROSZE = 500;

    /**
     * @param  Money $amount
     * @return Money
     */
    public function round(Money $amount): Money
    {
        // Convert the amount to grosze
        $cents = $amount->getAmount();

        // Round to the nearest 5 PLN
        $roundedCents = (int) (ceil($cents / self::ROUND_GROSZE) * self::ROUND_GROSZE);

        // Create a new Money object with the rounded PLN value
        return Money::PLN($roundedCents);
    }
}
