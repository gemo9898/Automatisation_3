<?php

namespace Tests\Unit\Entity;

use App\Entity\Wallet;
use PHPUnit\Framework\TestCase;

class WalletTest extends TestCase
{
    public function testInitialBalanceIsZero(): void
    {
        $wallet = new Wallet('USD');
        $this->assertSame(0.0, $wallet->getBalance());
    }

    public function testAddFundIncreasesBalance(): void
    {
        $wallet = new Wallet('USD');
        $wallet->addFund(50.0);
        $this->assertSame(50.0, $wallet->getBalance());
    }

    /**
     * @dataProvider invalidAmountProvider
     */
    public function testAddFundWithInvalidAmountThrowsException(float $invalidAmount): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Invalid amount');
        $wallet = new Wallet('USD');
        $wallet->addFund($invalidAmount);
    }

    public function testRemoveFundDecreasesBalance(): void
    {
        $wallet = new Wallet('USD');
        $wallet->addFund(100.0);
        $wallet->removeFund(30.0);
        $this->assertSame(70.0, $wallet->getBalance());
    }

    public function testRemoveFundMoreThanBalanceThrowsException(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Insufficient funds');
        $wallet = new Wallet('USD');
        $wallet->addFund(20.0);
        $wallet->removeFund(30.0);
    }

    /**
     * @dataProvider validCurrencyProvider
     */
    public function testSetCurrencyValid(string $currency): void
    {
        $wallet = new Wallet($currency);
        $this->assertSame($currency, $wallet->getCurrency());
    }

    /**
     * @dataProvider invalidCurrencyProvider
     */
    public function testSetCurrencyInvalid(string $invalidCurrency): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Invalid currency');
        new Wallet($invalidCurrency);
    }

    public function testSetBalanceValid(): void
    {
        $wallet = new Wallet('USD');
        $wallet->setBalance(150.0);
        $this->assertSame(150.0, $wallet->getBalance());
    }

    public function testSetBalanceInvalid(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Invalid balance');
        $wallet = new Wallet('USD');
        $wallet->setBalance(-50.0);
    }

    public function invalidAmountProvider(): array
    {
        return [
            [-10.0], [-5.5]
        ];
    }

    public function validCurrencyProvider(): array
    {
        return [['USD'], ['EUR']];
    }

    public function invalidCurrencyProvider(): array
    {
        return [['GBP'], ['MXN']];
    }
}
