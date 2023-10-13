<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Application\Factory;

use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Money;
use Money\MoneyParser;
use Money\Parser\DecimalMoneyParser;
use PragmaGoTech\Interview\Domain\Exception\Money\AmountPrecisionException;
use PragmaGoTech\Interview\Domain\Factory\MoneyFactory;

class PLNMoneyFactory implements MoneyFactory
{
    private const CURRENCY = 'PLN';

    private Currency $currency;

    private MoneyParser $moneyParser;

    public function __construct()
    {
        $currencies = new ISOCurrencies();
        $this->moneyParser = new DecimalMoneyParser($currencies);
        $this->currency = new Currency(self::CURRENCY);
    }

    /**
     * @throws AmountPrecisionException
     */
    public function createMoney(string $amount): Money
    {
        if ($this->hasCorrectPrecision($amount)) {
            throw new AmountPrecisionException('Max precision for PLN is two decimal places.');
        }

        return $this->moneyParser->parse($amount, $this->currency);
    }

    /**
     * @param  string $amount
     * @return bool
     */
    private function hasCorrectPrecision(string $amount): bool
    {
        return preg_match('/\.\d{3,}$/', $amount) !== 0;
    }
}
