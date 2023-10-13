<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Domain\ValueObject;

use PragmaGoTech\Interview\Domain\Exception\Loan\LoanTermException;

enum LoanTerm: int
{
    case TERM_12 = 12;
    case TERM_24 = 24;

    /**
     * @throws LoanTermException
     */
    public static function fromInt(int $value): LoanTerm
    {
        foreach (self::cases() as $case) {
            if ($case->toInt() === $value) {
                return $case;
            }
        }
        throw new LoanTermException(
            sprintf('Invalid value of LoanTerm. Allowed: %s', implode(', ', array_column(self::cases(), 'value')))
        );
    }

    /**
     * @return int
     */
    public function toInt(): int
    {
        return $this->value;
    }
}
