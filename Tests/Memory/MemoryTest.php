<?php

namespace Tdn\PhpTypes\Tests\Memory;

use Tdn\PhpTypes\Memory\Memory;

/**
 * Class MemoryTest.
 */
class MemoryTest extends \PHPUnit_Framework_TestCase
{
    public function testDefaults()
    {
        $this->assertTrue(Memory::isGcEnabled());
        $this->assertEquals(0, Memory::getEntriesCount());
        $this->assertEquals(0, Memory::getLastAddress());
        $this->assertEquals(10000, Memory::GC_ROOT_BUFFER_MAX_ENTRIES);
    }

    public function testGcOption()
    {
        Memory::setGcEnabled(false);
        $this->assertFalse(Memory::isGcEnabled());
        Memory::setGcEnabled(true);
    }

    public function testAddress()
    {
        $qux = 'bar';
        $memoryId = Memory::getNewAddress($qux);
        $this->assertEquals(1, $memoryId);
        $this->assertEquals($memoryId, Memory::getLastAddress());
        $abc = 'abc';
        Memory::setAddress(PHP_INT_MAX, $abc);
        $xyz = 'xyz';
        $this->assertFalse(is_numeric(Memory::getNewAddress($xyz)));
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Address already exists in memory.
     */
    public function testBadAddress()
    {
        $qux = 'bar';
        $memoryId = Memory::getNewAddress($qux);
        $this->assertEquals(1, $memoryId);
        Memory::setAddress(1, $qux); // Throws exception
    }

    public function testFree()
    {
        $this->assertEquals(0, Memory::getEntriesCount());
        $foo = 'var';
        Memory::getNewAddress($foo);
        $this->assertEquals(1, Memory::getEntriesCount());
        $this->assertEquals(1, count(Memory::getCollection()));
        $foo = null;
        $this->assertTrue(Memory::shouldFree(1)); //This happens automatically on objects when null. (calls destruct)
        $this->assertEquals(0, Memory::getEntriesCount());
        $this->assertFalse(Memory::shouldFree(9999));
    }

    /**
     * @expectedException \OutOfBoundsException
     * @expectedExceptionMessage Address does not exist.
     */
    public function testShutdown()
    {
        $qux = 'bar';
        $memoryId = Memory::getNewAddress($qux);
        Memory::shutdown();
        $pointer = Memory::getPointer($memoryId);
    }

    protected function tearDown()
    {
        Memory::close();
    }
}
