<?php

namespace Tdn\PhpTypes\Tests\Type;

use Tdn\PhpTypes\Type\CollectionType;
use Tdn\PhpTypes\Type\StringType;
use Tdn\PhpTypes\Type\Type;

/**
 * Class CollectionTypeTest.
 */
class CollectionTypeTest extends AbstractTypeTest
{
    public function testType()
    {
        $this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', new CollectionType());
        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', new CollectionType());
    }

    public function testBox()
    {
        /* @var CollectionType $myCollection */
        CollectionType::box($myCollection, new CollectionType());
        /* @var CollectionType $myOtherCollection */
        CollectionType::box($myOtherCollection, []);

        $this->assertInstanceOf(CollectionType::class, $myCollection);
        $this->assertInstanceOf(CollectionType::class, $myOtherCollection);

        $myCollection = [1, 2, 3, 4, 5];
        $myOtherCollection = new CollectionType(['1', '2', '3', '4', '5']);

        $this->assertInstanceOf(CollectionType::class, $myCollection);
        $this->assertInstanceOf(CollectionType::class, $myOtherCollection);
        $this->assertEquals([1, 2, 3, 4, 5], $myCollection->toArray());
        $this->assertEquals(['1', '2', '3', '4', '5'], $myOtherCollection->toArray());
    }

    public function testUnbox()
    {
        /* @var CollectionType $collection */
        CollectionType::box($collection, ['foo', 'bar']);
        $this->assertEquals('foo, bar', $collection(Type::STRING));
        $collection = ['baz', 'qux'];
        $this->assertEquals('baz, qux', $collection(Type::STRING));
        $this->assertEquals(['baz', 'qux'], $collection());
        $this->assertEquals(['baz', 'qux'], $collection(Type::ARRAY));
        $collection = ['foo', 'bar', 'baz', 'qux'];
        $this->assertEquals(4, $collection(Type::INT)); //"Cast" to int returns count
    }

    /**
     * @expectedException \Tdn\PhpTypes\Exception\InvalidTransformationException
     * @expectedExceptionMessage Could not transform CollectionType to float.
     */
    public function testUnboxFoatFail()
    {
        /* @var CollectionType $collection */
        CollectionType::box($collection, []);
        $collection(Type::FLOAT);
    }

    /**
     * @expectedException \Tdn\PhpTypes\Exception\InvalidTransformationException
     * @expectedExceptionMessage Could not transform CollectionType to bool.
     */
    public function testUnboxBoolFail()
    {
        /* @var CollectionType $collection */
        CollectionType::box($collection, []);
        $collection(Type::BOOL);
    }

    /**
     * @expectedException \TypeError
     * @expectedExceptionMessageRegExp /Argument ([0-9]+) passed to (.*) must be of the type array, string given.*$/
     */
    public function testBoxBreak()
    {
        CollectionType::box($myCollection, []);
        $myCollection = 'foo';
        $this->fail($myCollection);
    }

    public function testValue()
    {
        /* @var CollectionType $myCollection */
        CollectionType::box($myCollection, ['foo']);
        $this->assertEquals(['foo'], $myCollection());
    }

    public function testFrom()
    {
        $resource = tmpfile();

        $this->assertEquals([10.0], (CollectionType::valueOf(10.0))());
        $this->assertEquals([100], (CollectionType::valueOf(100))());
        $this->assertEquals(['bar'], (CollectionType::valueOf('bar'))());
        $this->assertEquals([false], (CollectionType::valueOf(false))());
        $this->assertEquals([new \stdClass()], (CollectionType::valueOf(new \stdClass()))());
        $this->assertEquals([$resource], (CollectionType::valueOf($resource))());
        $this->assertEquals(['qux'], (CollectionType::valueOf(['qux']))());
        $this->assertEquals(['foo'], (CollectionType::valueOf(new StringType('foo')))());
        $this->assertEquals(['xxyy'], (CollectionType::valueOf(new CollectionType(['xxyy'])))());
    }

    /**
     * @expectedException \Tdn\PhpTypes\Exception\InvalidTransformationException
     * @expectedExceptionMessage Could not transform null to CollectionType.
     */
    public function testBadFrom()
    {
        CollectionType::valueOf(null);
    }

    public function testTransmutable()
    {
        $this->assertEquals(new StringType('bar, baz'), (new CollectionType(['bar', 'baz']))->toString());
        $this->assertEquals(new CollectionType(['bar', 'baz']), (new CollectionType(['bar', 'baz']))->toCollection());
    }

    /**
     * @expectedException \Tdn\PhpTypes\Exception\InvalidTransformationException
     * @expectedExceptionMessage Could not transform object to BooleanType.
     */
    public function testBadTransmutableBool()
    {
        (new CollectionType(['bar', 'baz']))->toBool();
    }

    /**
     * @expectedException \Tdn\PhpTypes\Exception\InvalidTransformationException
     * @expectedExceptionMessage Could not transform object to IntType.
     */
    public function testBadTransmutableInt()
    {
        (new CollectionType(['bar', 'baz']))->toInt();
    }

    /**
     * @expectedException \Tdn\PhpTypes\Exception\InvalidTransformationException
     * @expectedExceptionMessage Could not transform object to FloatType.
     */
    public function testBadTransmutableFloat()
    {
        (new CollectionType(['bar', 'baz']))->toFloat();
    }
}
