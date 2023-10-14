<?php

declare(strict_types=1);

namespace PragmaGoTech\Tests\Unit\Interview\Application\Factory;

use Codeception\Test\Unit;
use Money\Money;
use PragmaGoTech\Interview\Application\Factory\PLNMoneyFactory;
use PragmaGoTech\Interview\Domain\Exception\Money\AmountPrecisionException;

class PLNMoneyFactoryTest extends Unit
{
    public function testCreateMoneyShouldReturnValidMoneyObject()
    {
        $factory = new PLNMoneyFactory();
        $amount = '100.69'; // Valid amount with two decimal places
        $money = $factory->createMoney($amount);

        $this->assertInstanceOf(Money::class, $money);
        $this->assertEquals(10069, $money->getAmount()); // Amount is converted to cents
    }

    public function testCreateMoneyWithIncorrectPrecisionShouldThrowException()
    {
        $factory = new PLNMoneyFactory();
        $amount = '100.666'; // Invalid amount with three decimal places

        $this->expectException(AmountPrecisionException::class);
        $factory->createMoney($amount);
    }

    public function testCreateMoneyWithZeroAmountShouldReturnValidMoneyObject()
    {
        $factory = new PLNMoneyFactory();
        $amount = '0.00'; // Zero amount with two decimal places
        $money = $factory->createMoney($amount);

        $this->assertInstanceOf(Money::class, $money);
        $this->assertEquals(0, $money->getAmount());
    }

    public function testCreateMoneyWithOneDecimalPlaceShouldReturnValidMoneyObject()
    {
        $factory = new PLNMoneyFactory();
        $amount = '100.5'; // Valid amount with one decimal place
        $money = $factory->createMoney($amount);

        $this->assertInstanceOf(Money::class, $money);
        $this->assertEquals(10050, $money->getAmount());
    }

    public function testCreateMoneyWithLargeAmountShouldReturnValidMoneyObject()
    {
        $factory = new PLNMoneyFactory();
        $amount = '1000000000.99'; // Valid large amount with two decimal places
        $money = $factory->createMoney($amount);

        $this->assertInstanceOf(Money::class, $money);
        $this->assertEquals(100000000099, $money->getAmount());
    }

    public function testCreateMoneyWithNegativeAmountShouldReturnValidMoneyObject()
    {
        $factory = new PLNMoneyFactory();
        $amount = '-100.00'; // Negative amount with two decimal places
        $money = $factory->createMoney($amount);

        $this->assertInstanceOf(Money::class, $money);
        $this->assertEquals(-10000, $money->getAmount());
    }
}
