<?php

namespace Tdn\PhpTypes\Tests\Type;

use Tdn\PhpTypes\Type\Collection;
use Tdn\PhpTypes\Type\IntType;
use Tdn\PhpTypes\Type\StringType;
use Tdn\PhpTypes\Type\Type;
use Doctrine\Common\Collections\Criteria;

/**
 * Class CollectionTest.
 */
class CollectionTest extends AbstractTypeTest
{
    /**
     * @dataProvider provideDifferentElements
     */
    public function testToArray($elements)
    {
        $collection = new Collection($elements);

        $this->assertSame($elements, $collection->toArray());
    }

    /**
     * @dataProvider provideDifferentElements
     */
    public function testFirst($elements)
    {
        $collection = new Collection($elements);
        $this->assertSame(reset($elements), $collection->first());
    }

    /**
     * @dataProvider provideDifferentElements
     */
    public function testLast($elements)
    {
        $collection = new Collection($elements);
        $this->assertSame(end($elements), $collection->last());
    }

    /**
     * @dataProvider provideDifferentElements
     */
    public function testKey($elements)
    {
        $collection = new Collection($elements);

        $this->assertSame(key($elements), $collection->key());

        next($elements);
        $collection->next();

        $this->assertSame(key($elements), $collection->key());
    }

    /**
     * @dataProvider provideDifferentElements
     */
    public function testNext($elements)
    {
        $collection = new Collection($elements);

        while (true) {
            $collectionNext = $collection->next();
            $arrayNext = next($elements);

            if (!$collectionNext || !$arrayNext) {
                break;
            }

            $this->assertSame($arrayNext, $collectionNext, 'Returned value of Collection::next() and next() not match');
            $this->assertSame(key($elements), $collection->key(), 'Keys not match');
            $this->assertSame(current($elements), $collection->current(), 'Current values not match');
        }
    }

    /**
     * @dataProvider provideDifferentElements
     */
    public function testCurrent($elements)
    {
        $collection = new Collection($elements);

        $this->assertSame(current($elements), $collection->current());

        next($elements);
        $collection->next();

        $this->assertSame(current($elements), $collection->current());
    }

    /**
     * @dataProvider provideDifferentElements
     */
    public function testGetKeys($elements)
    {
        $collection = new Collection($elements);

        $this->assertSame(array_keys($elements), $collection->getKeys());
    }

    /**
     * @dataProvider provideDifferentElements
     */
    public function testGetValues($elements)
    {
        $collection = new Collection($elements);

        $this->assertSame(array_values($elements), $collection->getValues());
    }

    /**
     * @dataProvider provideDifferentElements
     */
    public function testCount($elements)
    {
        $collection = new Collection($elements);

        $this->assertSame(count($elements), $collection->count());
    }

    /**
     * @dataProvider provideDifferentElements
     */
    public function testIterator($elements)
    {
        $collection = new Collection($elements);

        $iterations = 0;
        foreach ($collection->getIterator() as $key => $item) {
            $this->assertSame($elements[$key], $item, "Item {$key} not match");
            ++$iterations;
        }

        $this->assertEquals(count($elements), $iterations, 'Number of iterations not match');
    }

    /**
     * @return array
     */
    public function provideDifferentElements()
    {
        return array(
            'indexed' => array(array(1, 2, 3, 4, 5)),
            'associative' => array(array('A' => 'a', 'B' => 'b', 'C' => 'c')),
            'mixed' => array(array('A' => 'a', 1, 'B' => 'b', 2, 3)),
        );
    }

    public function testUnique()
    {
        $elements = ['bar', 'baz', 'baz'];
        $collection = new Collection($elements);

        $this->assertEquals(array_unique($elements), $collection->unique()->toArray());
    }

    /**
     * @expectedException \LogicException
     * @expectedExceptionMessage Collection instance is not typed, or type has no string support.
     */
    public function testBadUnique()
    {
        $elements = [2, 'baz', 5];
        $collection = new Collection($elements);

        $collection->unique();
    }

    public function testUnshift()
    {
        $elements = ['foo', 'bar', 'baz'];
        $collection = new Collection($elements);
        $collection->unshift('qux');
        array_unshift($elements, 'qux');

        $this->assertEquals($elements, $collection->toArray());
    }

    public function testRemove()
    {
        $elements = array(1, 'A' => 'a', 2, 'B' => 'b', 3, 'foo' => 'bar');
        $collection = new Collection($elements);

        $this->assertEquals(1, $collection->remove(0));
        unset($elements[0]);

        $this->assertEquals(null, $collection->remove('non-existent'));
        unset($elements['non-existent']);

        $this->assertEquals(2, $collection->remove(1));
        unset($elements[1]);

        $this->assertEquals('a', $collection->remove('A'));
        unset($elements['A']);

        $this->assertEquals('bar', $collection->offsetUnset('foo'));
        unset($elements['foo']);

        $this->assertEquals($elements, $collection->toArray());
    }

    public function testRemoveElement()
    {
        $elements = array(1, 'A' => 'a', 2, 'B' => 'b', 3, 'A2' => 'a', 'B2' => 'b');
        $collection = new Collection($elements);

        $this->assertTrue($collection->removeElement(1));
        unset($elements[0]);

        $this->assertFalse($collection->removeElement('non-existent'));

        $this->assertTrue($collection->removeElement('a'));
        unset($elements['A']);

        $this->assertTrue($collection->removeElement('a'));
        unset($elements['A2']);

        $this->assertEquals($elements, $collection->toArray());
    }

    public function testContainsKey()
    {
        $elements = array(1, 'A' => 'a', 2, 'null' => null, 3, 'A2' => 'a', 'B2' => 'b');
        $collection = new Collection($elements);

        $this->assertTrue($collection->containsKey(0), 'Contains index 0');
        $this->assertTrue($collection->containsKey('A'), 'Contains key "A"');
        $this->assertTrue($collection->containsKey('null'), 'Contains key "null", with value null');
        $this->assertFalse($collection->containsKey('non-existent'), "Doesn't contain key");
        $this->assertTrue($collection->offsetExists('null'), 'Contains key "null", with value null');
    }

    public function testEmpty()
    {
        $collection = new Collection();
        $this->assertTrue($collection->isEmpty(), 'Empty collection');

        $collection->add(1);
        $this->assertFalse($collection->isEmpty(), 'Not empty collection');
    }

    public function testContains()
    {
        $elements = array(1, 'A' => 'a', 2, 'null' => null, 3, 'A2' => 'a', 'zero' => 0);
        $collection = new Collection($elements);

        $this->assertTrue($collection->contains(0), 'Contains Zero');
        $this->assertTrue($collection->contains('a'), 'Contains "a"');
        $this->assertTrue($collection->contains(null), 'Contains Null');
        $this->assertFalse($collection->contains('non-existent'), "Doesn't contain an element");
    }

    public function testExists()
    {
        $elements = array(1, 'A' => 'a', 2, 'null' => null, 3, 'A2' => 'a', 'zero' => 0);
        $collection = new Collection($elements);

        $this->assertTrue($collection->exists(function ($key, $element) {
            return $key == 'A' && $element == 'a';
        }), 'Element exists');

        $this->assertFalse($collection->exists(function ($key, $element) {
            return $key == 'non-existent' && $element == 'non-existent';
        }), 'Element not exists');
    }

    public function testIndexOf()
    {
        $elements = array(1, 'A' => 'a', 2, 'null' => null, 3, 'A2' => 'a', 'zero' => 0);
        $collection = new Collection($elements);

        $this->assertSame(array_search(2, $elements, true), $collection->indexOf(2), 'Index of 2');
        $this->assertSame(array_search(null, $elements, true), $collection->indexOf(null), 'Index of null');
        $this->assertSame(
            array_search('non-existent', $elements, true),
            $collection->indexOf('non-existent'),
            'Index of non existent'
        );
    }

    public function testGet()
    {
        $elements = array(1, 'A' => 'a', 2, 'null' => null, 3, 'A2' => 'a', 'zero' => 0);
        $collection = new Collection($elements);

        $this->assertSame(2, $collection->get(1), 'Get element by index');
        $this->assertSame('a', $collection->get('A'), 'Get element by name');
        $this->assertSame(null, $collection->get('non-existent'), 'Get non existent element');
        $this->assertSame(2, $collection->offsetGet(1));
    }

    public function testMatchingWithSortingPreservesyKeys()
    {
        $object1 = new \stdClass();
        $object2 = new \stdClass();

        $object1->sortField = 2;
        $object2->sortField = 1;

        $collection = new Collection(array(
            'object1' => $object1,
            'object2' => $object2,
        ));

        $this->assertSame(
            array(
                'object2' => $object2,
                'object1' => $object1,
            ),
            $collection
                ->matching(new Criteria(null, array('sortField' => Criteria::ASC)))
                ->toArray()
        );
    }

    public function testType()
    {
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

    public function testTypedCollection()
    {
        $collection = new Collection(['foo', 'bar', 'baz'], StringType::class);
        $collection->add('qux');
        $collection->set('quux', 'quux');

        $this->assertEquals(5, $collection->count());
        foreach ($collection as $stringInstance) {
            $this->assertInstanceOf(StringType::class, $stringInstance);
        }
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

    public function testImplode()
    {
        $collection = new Collection(['foo', 'bar', 'baz']);
        $this->assertEquals(StringType::create('foo, bar, baz'), $collection->implode(', '));
    }

    /**
     * @expectedException \TypeError
     */
    public function testTypedCollectionFail()
    {
        new Collection([1, 2, 3, 'nan'], IntType::class);
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
        /* @var Collection $myCollection */
        Collection::box($myCollection, ['foo']);
        $expected = new Collection(['foo', 'bar']);
        $this->assertEquals($expected, $myCollection->merge($expected));
    }

    public function testMergeWithDupes()
    {
        /* @var Collection $myCollection */
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
