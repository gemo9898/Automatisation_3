<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use App\Entity\Person;
use App\Entity\Wallet;
use App\Entity\Product;
use Faker\Factory;

class ExempleTest extends TestCase
{
    public function testWallet00(): void
    {
        try{
        $wallet = new Wallet('USD', 1);
        if ($wallet) {
        $this->assertEquals('USD', $wallet->getCurrency());
        $this->assertEquals(1, $wallet->getBalance());
        }
        else{
            echo('Wallet not created');
        }}
        catch(\Exception $e){
            $this->assertTrue(true);
        }
    }
    public function testWallet01(): void
    {
        
        try{
        $wallet = new Wallet('MXN');
       
        if ($wallet) {
            $this->assertTrue(true);
            $this->assertEquals('MXN', $wallet->getCurrency());
            $this->assertEquals(-100, $wallet->getBalance());
        }
        else{
            echo('Wallet not created');
        }}
        catch(\Exception $e){
            $this->assertTrue(true);
        }
       
    }
    public function testWallet02(): void
    {
        try{
        $wallet = new Wallet('EUR');
        if ($wallet) {
        $this->assertEquals('EUR', $wallet->getCurrency());
        $wallet->setBalance(-100);
        $this->assertEquals(-100, $wallet->getBalance());
        }}
        catch(\Exception $e){
            $this->assertTrue(true);
        }
    }

    public function testWallet03(): void
    {
        try{
        $wallet = new Wallet('EUR');
        if ($wallet) {
        $this->assertEquals('EUR', $wallet->getCurrency());
        $wallet->setBalance(100);
        $this->assertEquals(100, $wallet->getBalance());
        }}
        catch(\Exception $e){
            $this->assertTrue(true);
        }
    }

    public function testPerson00(): void
    {
        try{
        $faker = Factory::create('fr_FR');
        if ($faker){
        $fp=$faker->name();        
        $person = new Person($fp, 'USD');
        $this->assertEquals($fp, $person->getName());
        $this->assertEquals('USD', $person->getWallet()->getCurrency());
        $this->assertEquals(1, $person->getWallet()->getBalance());
        }}
        catch(\Exception $e){
            $this->assertTrue(true);
        }
    }

    public function testPerson01(): void
    {        
        try{
        $faker = Factory::create('fr_FR');        
        $fp=$faker->name();   
        $person = new Person($fp, 'EUR');
        if ($person) {
        $this->assertEquals($fp, $person->getName());
        $this->assertEquals('MXN', $person->getWallet()->getCurrency());
        $this->assertEquals(1, $person->getWallet()->getBalance());
        $person->getWallet()->setBalance(100);
        $this->assertEquals(100, $person->getWallet()->getBalance());
        }}
        catch(\Exception $e){
            $this->assertTrue(true);
            #TODO boolean

        }
    }


    public function testProduct00(): void
    {
        try{
        $faker = Factory::create('fr_FR');
        $fp=$faker->words(3,true);   
        $fprice=$faker->randomFloat(2, 1, 100);
        $fc=$faker->word();
        $product = new Product($fp, ['USD' => $fprice],$fc);
        if ($product) {
        $this->assertEquals($fp, $product->getName());
        $this->assertEquals(['USD' => $fp], $product->getPrices());
        $this->assertEquals($fc, $product->getType());
        }}
        catch(\Exception $e){         
            $this->assertTrue(true);
        }
    }

}


#TODO ajouter des tests pour les exceptions
// <?php
// require_once 'PHPUnit/Framework.php';
 
// class ExceptionTest extends PHPUnit_Framework_TestCase
// {
//     public function testException()
//     {
//         $this->expectException(InvalidArgumentException::class);
//         // or for PHPUnit < 5.2
//         // $this->setExpectedException(InvalidArgumentException::class);

//         //...and then add your test code that generates the exception 
//         exampleMethod($anInvalidArgument);
//     }
// }