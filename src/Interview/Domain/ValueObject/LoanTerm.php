<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Domain\ValueObject;

enum LoanTerm: int
{
    case TERM_12 = 12;
    case TERM_24 = 24;

    /**
     * @return int
     */
    public function toMonths(): int
    {
        return $this->value;
    }
}
