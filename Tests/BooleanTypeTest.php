<?php

namespace Tdn\PhpTypes\Tests;

use Tdn\PhpTypes\Type\BooleanType;

/**
 * Class BooleanTypeTest
 */
class BooleanTypeTest extends \PHPUnit_Framework_TestCase
{
    public function testBoolean()
    {
        $this->assertTrue(new BooleanType(true) == new BooleanType(true));
        $this->assertTrue((new BooleanType(true))->getValue());
        $this->assertFalse((new BooleanType(false))->getValue());
        $this->assertFalse(new BooleanType(false) == new BooleanType(true));
    }

    public function testValueOf()
    {
        $this->assertEquals(new BooleanType(false), BooleanType::valueOf(false));
        $this->assertEquals(new BooleanType(false), BooleanType::valueOf('false'));
        $this->assertEquals(new BooleanType(false), BooleanType::valueOf(null));
        $this->assertEquals(new BooleanType(true), BooleanType::valueOf('true'));
        $this->assertEquals(new BooleanType(true), BooleanType::valueOf(new \StdClass()));
        $this->assertEquals(new BooleanType(true), BooleanType::valueOf(tmpfile()));
        $this->assertEquals(
            new BooleanType(false),
            BooleanType::valueOf('any string that is not "true" evalutes to false')
        );
    }

    /**
     * @expectedException \Tdn\PhpTypes\Exception\InvalidTransformationException
     * @expectedExceptionMessage Could not transform array to boolean.
     */
    public function testArray()
    {
        BooleanType::valueOf([]);
    }

    /**
     * @expectedException \Tdn\PhpTypes\Exception\InvalidTransformationException
     * @expectedExceptionMessage Could not transform integer to boolean.
     */
    public function testInteger()
    {
        BooleanType::valueOf(99);
    }

    /**
     * @expectedException \Tdn\PhpTypes\Exception\InvalidTransformationException
     * @expectedExceptionMessage Could not transform double to boolean.
     */
    public function testDouble()
    {
        BooleanType::valueOf(3E-5);
    }
}
