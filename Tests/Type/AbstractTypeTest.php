<?php

namespace Tdn\PhpTypes\Tests\Type;

use Tdn\PhpTypes\Type\StringType;
use Tdn\PhpTypes\Type\Traits\Boxable;

/**
 * Class AbstractTypeTest.
 */
abstract class AbstractTypeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \LogicException
     * @expectedExceptionMessageRegExp /The identifier of type (.*) is defined more than once.*$/
     */
    public function testBadBoxType()
    {
        $notNullVar = false;
        Boxable::box($notNullVar);
    }

    /**
     * @expectedException \LogicException
     * @expectedExceptionMessageRegExp /(.*) implemented but no constructor method found in class: (.*)$/
     */
    public function testBadBoxImplementation()
    {
        Boxable::box($foo);
    }

    /**
     * @expectedException \OutOfBoundsException
     * @expectedExceptionMessageRegExp /Type (.*) not found. Valid types are (.*).$/
     */
    public function testBadTranlatedType()
    {
        StringType::box($foo, 'bar');
        $foo(PHP_INT_MAX);
    }
}
