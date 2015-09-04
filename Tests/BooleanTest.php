<?php

namespace Tdn\PhpTypes\Tests;

use Tdn\PhpTypes\Type\Boolean;

/**
 * Class BooleanTest
 * @package Tdn\PhpTypes\Tests
 */
class BooleanTest extends \PHPUnit_Framework_TestCase
{
    public function testBoolean()
    {
        $this->assertTrue(new Boolean(true) == new Boolean(true));
        $this->assertTrue((new Boolean(true))->getValue());
        $this->assertFalse((new Boolean(false))->getValue());
        $this->assertFalse(new Boolean(false) == new Boolean(true));
    }

    public function testValueOf()
    {
        $this->assertEquals(new Boolean(false), Boolean::valueOf(false));
        $this->assertEquals(new Boolean(false), Boolean::valueOf('any string that is not "true" evalutes to false'));
        $this->assertEquals(new Boolean(false), Boolean::valueOf('false'));
        $this->assertEquals(new Boolean(false), Boolean::valueOf(null));
        $this->assertEquals(new Boolean(true), Boolean::valueOf('true'));
        $this->assertEquals(new Boolean(true), Boolean::valueOf(new \StdClass()));
        $this->assertEquals(new Boolean(true), Boolean::valueOf(tmpfile()));
    }

    /**
     * @expectedException \Tdn\PhpTypes\Exception\InvalidTransformationException
     * @expectedExceptionMessage Could not transform array to boolean.
     */
    public function testArray()
    {
        Boolean::valueOf([]);
    }

    /**
     * @expectedException \Tdn\PhpTypes\Exception\InvalidTransformationException
     * @expectedExceptionMessage Could not transform integer to boolean.
     */
    public function testInteger()
    {
        Boolean::valueOf(99);
    }

    /**
     * @expectedException \Tdn\PhpTypes\Exception\InvalidTransformationException
     * @expectedExceptionMessage Could not transform double to boolean.
     */
    public function testDouble()
    {
        Boolean::valueOf(3E-5);
    }
}
