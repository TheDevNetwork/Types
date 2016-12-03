<?php

namespace Tdn\PhpTypes\Tests\Type;

use Tdn\PhpTypes\Type\StringType;

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
        StringType::box($notNullVar);
    }

    /**
     * @expectedException \TypeError
     * @expectedExceptionMessageRegExp /Argument (.*) passed to (.*) must be of the type (.*), none given.*$/
     */
    public function testBadBoxImplementation()
    {
        StringType::box($foo);
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
