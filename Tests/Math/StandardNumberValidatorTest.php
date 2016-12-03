<?php

namespace Tdn\PhpTypes\Tests\Math;

use Tdn\PhpTypes\Math\DefaultNumberValidator;

/**
 * Class StandardNumberValidatorTest.
 */
class StandardNumberValidatorTest extends \PHPUnit_Framework_TestCase
{
    public function testFailsObject()
    {
        $validator = new DefaultNumberValidator();
        $this->assertFalse($validator->isValid(new \stdClass()));
    }

    public function testFailsString()
    {
        $validator = new DefaultNumberValidator();
        $this->assertFalse($validator->isValid('ThisIsNotANumber'));
    }

    public function testFailsBool()
    {
        $validator = new DefaultNumberValidator();
        $this->assertFalse($validator->isValid(false));
    }

    public function testValidNumber()
    {
        $validator = new DefaultNumberValidator();
        $this->assertTrue($validator->isValid(3));
        $this->assertTrue($validator->isValid('4'));
        $this->assertTrue($validator->isValid(43.029));
        $this->assertTrue($validator->isValid('39.039'));
        $this->assertTrue($validator->isValid(1.2e3));
        $this->assertTrue($validator->isValid('1.2e3'));
        $this->assertTrue($validator->isValid(7E-10));
        $this->assertTrue($validator->isValid('7E-10'));
    }
}
