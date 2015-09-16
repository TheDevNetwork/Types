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

    public function testFrom()
    {
        $this->assertEquals(new BooleanType(false), BooleanType::from(false));
        $this->assertEquals(new BooleanType(false), BooleanType::from('false'));
        $this->assertEquals(new BooleanType(false), BooleanType::from(null));
        $this->assertEquals(new BooleanType(true), BooleanType::from('true'));
        $this->assertEquals(new BooleanType(true), BooleanType::from(new \StdClass()));
        $this->assertEquals(new BooleanType(true), BooleanType::from(tmpfile()));
        $this->assertEquals(
            new BooleanType(false),
            BooleanType::from('any string that is not "true" evalutes to false')
        );
    }

    /**
     * @expectedException \Tdn\PhpTypes\Exception\InvalidTransformationException
     * @expectedExceptionMessage Could not transform array to boolean.
     */
    public function testArray()
    {
        BooleanType::from([]);
    }

    /**
     * @expectedException \Tdn\PhpTypes\Exception\InvalidTransformationException
     * @expectedExceptionMessage Could not transform integer to boolean.
     */
    public function testInteger()
    {
        BooleanType::from(99);
    }

    /**
     * @expectedException \Tdn\PhpTypes\Exception\InvalidTransformationException
     * @expectedExceptionMessage Could not transform double to boolean.
     */
    public function testDouble()
    {
        BooleanType::from(3E-5);
    }
}
