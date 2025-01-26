<?php

namespace Tests\Unit\Entity;

use App\Entity\Person;
use App\Entity\Product;
use App\Entity\Wallet;
use PHPUnit\Framework\TestCase;

class PersonTest extends TestCase
{
    public function testPersonCreation(): void
    {
        $person = new Person('Alice', 'USD');
        $this->assertSame('Alice', $person->getName());
        $this->assertSame('USD', $person->getWallet()->getCurrency());
    }

    public function testTransferFundSameCurrency(): void
    {
        $person1 = new Person('Alice', 'USD');
        $person1->getWallet()->addFund(100.0);
        $person2 = new Person('Bob', 'USD');
        $person1->transfertFund(50.0, $person2);
        $this->assertSame(50.0, $person1->getWallet()->getBalance());
        $this->assertSame(50.0, $person2->getWallet()->getBalance());
    }

    public function testTransferFundDifferentCurrencyThrowsException(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Can\'t give money with different currencies');
        $person1 = new Person('Alice', 'USD');
        $person1->getWallet()->addFund(100.0);
        $person2 = new Person('Bob', 'EUR');
        $person1->transfertFund(50.0, $person2);
    }

    public function testBuyProductWithCorrectCurrency(): void
    {
        $person = new Person('Alice', 'USD');
        $person->getWallet()->addFund(100.0);
        $product = new Product('Product1', ['USD' => 50.0], 'tech');
        $person->buyProduct($product);
        $this->assertSame(50.0, $person->getWallet()->getBalance());
    }

    public function testBuyProductWithWrongCurrencyThrowsException(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Can\'t buy product with this wallet currency');
        $person = new Person('Alice', 'EUR');
        $person->getWallet()->addFund(100.0);
        $product = new Product('Product1', ['USD' => 50.0], 'tech');
        $person->buyProduct($product);
    }

    public function testDivideWalletEvenlyAmongPersons(): void
    {
        $person1 = new Person('Alice', 'USD');
        $person1->getWallet()->addFund(100.0);
        $person2 = new Person('Bob', 'USD');
        $person3 = new Person('Charlie', 'USD');
        $person1->divideWallet([$person2, $person3]);
        $this->assertEqualsWithDelta(0.0, $person1->getWallet()->getBalance(), 0.001);
        $this->assertEqualsWithDelta(50.0, $person2->getWallet()->getBalance(), 0.001);
        $this->assertEqualsWithDelta(50.0, $person3->getWallet()->getBalance(), 0.001);
    }

    public function testDivideWalletWithDifferentCurrenciesIgnoresThem(): void
    {
        $person1 = new Person('Alice', 'USD');
        $person1->getWallet()->addFund(100.0);
        $person2 = new Person('Bob', 'EUR');
        $person3 = new Person('Charlie', 'USD');
        $person1->divideWallet([$person2, $person3]);
        $this->assertSame(0.0, $person1->getWallet()->getBalance());
        $this->assertSame(0.0, $person2->getWallet()->getBalance());
        $this->assertSame(100.0, $person3->getWallet()->getBalance());
    }

    public function testHasFundReturnsCorrectBoolean(): void
    {
        $personWithFund = new Person('Alice', 'USD');
        $personWithFund->getWallet()->addFund(10.0);
        $personWithoutFund = new Person('Bob', 'USD');
        $this->assertTrue($personWithFund->hasFund());
        $this->assertFalse($personWithoutFund->hasFund());
    }

    public function testDivideWalletWithNoValidPersonsThrowsError(): void
    {
        $this->expectException(\DivisionByZeroError::class);
        $person1 = new Person('Alice', 'USD');
        $person1->getWallet()->addFund(100.0);
        $person2 = new Person('Bob', 'EUR');
        $person1->divideWallet([$person2]);
    }
}
