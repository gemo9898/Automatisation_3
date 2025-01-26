<?php

namespace Tests\Unit\Entity;

use App\Entity\Product;
use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{
    public function testCreateProductWithValidType(): void
    {
        $product = new Product('Test', ['USD' => 10.0], 'tech');
        $this->assertSame('tech', $product->getType());
    }

    public function testCreateProductWithInvalidTypeThrowsException(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Invalid type');
        new Product('Test', ['USD' => 10.0], 'invalid_type');
    }

    public function testSetPricesFiltersInvalidCurrenciesAndNegative(): void
    {
        $product = new Product('Test', [
            'USD' => 10.0,
            'MXN' => -5.0,
            'EUR' => 15.0
        ], 'tech');
        $prices = $product->getPrices();
        $this->assertArrayHasKey('USD', $prices);
        $this->assertArrayHasKey('EUR', $prices);
        $this->assertArrayNotHasKey('MXN', $prices);
        $this->assertCount(2, $prices);
    }

    public function testGetPriceWithValidCurrency(): void
    {
        $product = new Product('Test', ['USD' => 10.0], 'tech');
        $this->assertSame(10.0, $product->getPrice('USD'));
    }

    public function testGetPriceWithInvalidCurrencyThrowsException(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Currency not available for this product');
        $product = new Product('Test', ['USD' => 10.0], 'tech');
        $product->getPrice('EUR');
    }

    public function testGetTvaForFoodAndOtherTypes(): void
    {
        $foodProduct = new Product('Food', ['USD' => 10.0], 'food');
        $techProduct = new Product('Tech', ['USD' => 100.0], 'tech');
        $this->assertSame(0.1, $foodProduct->getTVA());
        $this->assertSame(0.2, $techProduct->getTVA());
    }

    public function testListCurrenciesReturnsCorrectOnes(): void
    {
        $product = new Product('Test', ['USD' => 10.0, 'EUR' => 8.5], 'tech');
        $this->assertSame(['USD', 'EUR'], $product->listCurrencies());
    }
}
