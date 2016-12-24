<?php

namespace Tdn\PhpTypes\Tests\Type;

use Tdn\PhpTypes\Type\Collection;
use Tdn\PhpTypes\Type\StringType;
use Tdn\PhpTypes\Type\Type;

/**
 * Class CollectionTest.
 */
class CollectionTest extends AbstractTypeTest
{
    public function testType()
    {
        $this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', new Collection());
        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', new Collection());
    }

    public function testBox()
    {
        /* @var Collection $myCollection */
        Collection::box($myCollection, new Collection());
        /* @var Collection $myOtherCollection */
        Collection::box($myOtherCollection, []);

        $this->assertInstanceOf(Collection::class, $myCollection);
        $this->assertInstanceOf(Collection::class, $myOtherCollection);

        $myCollection = [1, 2, 3, 4, 5];
        $myOtherCollection = new Collection(['1', '2', '3', '4', '5']);

        $this->assertInstanceOf(Collection::class, $myCollection);
        $this->assertInstanceOf(Collection::class, $myOtherCollection);
        $this->assertEquals([1, 2, 3, 4, 5], $myCollection->toArray());
        $this->assertEquals(['1', '2', '3', '4', '5'], $myOtherCollection->toArray());
    }

    public function testUnbox()
    {
        /* @var Collection $collection */
        Collection::box($collection, ['foo', 'bar']);
        $this->assertEquals('foo, bar', $collection(Type::STRING));
        $collection = ['baz', 'qux'];
        $this->assertEquals('baz, qux', $collection(Type::STRING));
        $this->assertEquals(['baz', 'qux'], $collection());
        $this->assertEquals(['baz', 'qux'], $collection(Type::ARRAY));
        $collection = ['foo', 'bar', 'baz', 'qux'];
        $this->assertEquals(4, $collection(Type::INT)); //"Cast" to int returns count
    }

    /**
     * @expectedException \Tdn\PhpTypes\Exception\InvalidTypeCastException
     * @expectedExceptionMessage Could not cast Collection to string.
     */
    public function testBadUnboxString()
    {
        /* @var Collection $collection */
        Collection::box($collection, [tmpfile(), new \stdClass()]);
        $collection(Type::STRING);
    }

    /**
     * @expectedException \Tdn\PhpTypes\Exception\InvalidTypeCastException
     * @expectedExceptionMessage Could not cast Collection to float.
     */
    public function testUnboxFoatFail()
    {
        /* @var Collection $collection */
        Collection::box($collection, []);
        $collection(Type::FLOAT);
    }

    /**
     * @expectedException \Tdn\PhpTypes\Exception\InvalidTypeCastException
     * @expectedExceptionMessage Could not cast Collection to bool.
     */
    public function testUnboxBoolFail()
    {
        /* @var Collection $collection */
        Collection::box($collection, []);
        $collection(Type::BOOL);
    }

    /**
     * @expectedException \TypeError
     * @expectedExceptionMessageRegExp /Argument ([0-9]+) passed to (.*) must be of the type array, string given.*$/
     */
    public function testBoxBreak()
    {
        Collection::box($myCollection, []);
        $myCollection = 'foo';
        $this->fail($myCollection);
    }

    public function testValue()
    {
        /* @var Collection $myCollection */
        Collection::box($myCollection, ['foo']);
        $this->assertEquals(['foo'], $myCollection());
    }

    public function testMerge()
    {
        /** @var Collection $myCollection */
        Collection::box($myCollection, ['foo']);
        $expected = new Collection(['foo', 'bar']);
        $this->assertEquals($expected, $myCollection->merge($expected));
    }

    public function testMergeWithDupes()
    {
        /** @var Collection $myCollection */
        Collection::box($myCollection, ['foo']);
        $this->assertEquals(
            new Collection(['foo', 'foo', 'bar']),
            $myCollection->merge(new Collection(['foo', 'bar']), true)
        );
    }

    public function testFrom()
    {
        $resource = tmpfile();

        $this->assertEquals([10.0], (Collection::valueOf(10.0))());
        $this->assertEquals([100], (Collection::valueOf(100))());
        $this->assertEquals(['bar'], (Collection::valueOf('bar'))());
        $this->assertEquals([false], (Collection::valueOf(false))());
        $this->assertEquals([new \stdClass()], (Collection::valueOf(new \stdClass()))());
        $this->assertEquals([$resource], (Collection::valueOf($resource))());
        $this->assertEquals(['qux'], (Collection::valueOf(['qux']))());
        $this->assertEquals(['foo'], (Collection::valueOf(new StringType('foo')))());
        $this->assertEquals(['xxyy'], (Collection::valueOf(new Collection(['xxyy'])))());
    }

    /**
     * @expectedException \Tdn\PhpTypes\Exception\InvalidTransformationException
     * @expectedExceptionMessage Could not transform null to Collection.
     */
    public function testBadFrom()
    {
        Collection::valueOf(null);
    }

    public function testTransmutable()
    {
        $this->assertEquals(new StringType('bar, baz'), (new Collection(['bar', 'baz']))->toStringType());
        $this->assertEquals(new Collection(['bar', 'baz']), (new Collection(['bar', 'baz']))->toCollection());
    }

    /**
     * @expectedException \Tdn\PhpTypes\Exception\InvalidTransformationException
     * @expectedExceptionMessage Could not transform object to BooleanType.
     */
    public function testBadTransmutableBool()
    {
        (new Collection(['bar', 'baz']))->toBoolType();
    }

    /**
     * @expectedException \Tdn\PhpTypes\Exception\InvalidTransformationException
     * @expectedExceptionMessage Could not transform object to IntType.
     */
    public function testBadTransmutableInt()
    {
        (new Collection(['bar', 'baz']))->toIntType();
    }

    /**
     * @expectedException \Tdn\PhpTypes\Exception\InvalidTransformationException
     * @expectedExceptionMessage Could not transform object to FloatType.
     */
    public function testBadTransmutableFloat()
    {
        (new Collection(['bar', 'baz']))->toFloatType();
    }
}
