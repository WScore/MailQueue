<?php


use PHPUnit\Framework\TestCase;
use WScore\MailQueue\Mail\MailAddress;

class MailAddressTest extends TestCase
{
    public function testConstructor()
    {
        $address = new MailAddress('test@example.com', 'test example');
        $this->assertEquals('test@example.com', $address->getAddress());
        $this->assertEquals('test example', $address->getName());
    }

    public function testNoName()
    {
        $address = new MailAddress('test2@example.com');
        $this->assertEquals('test2@example.com', $address->getAddress());
        $this->assertFalse($address->hasName());
        $this->assertEquals('', $address->getName());

        $address = new MailAddress('test2@example.com', '');
        $this->assertFalse($address->hasName());
    }

    public function testToArray()
    {
        $address = new MailAddress('test@example.com', 'test example');
        $array = [
            'address' => 'test@example.com',
            'name' => 'test example',
        ];
        $this->assertEquals($array, $address->toArray());
        $this->assertEquals(json_encode($array), $address->toJSON());

        $array = [
            'address' => 'test@example.com',
        ];
        $address = new MailAddress('test@example.com');
        $this->assertEquals($array, $address->toArray());
        $this->assertEquals(json_encode($array), $address->toJSON());
    }
}
