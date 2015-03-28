<?php

namespace Tdn\PhpTypes\Tests;

use Tdn\PhpTypes\Type\DateTime;

/**
 * Class DateTimeTest
 * @package Tdn\PhpTypes\Tests
 */
class DateTimeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var DateTime
     */
    protected $dateTime;

    protected function setUp()
    {
        $this->dateTime = new DateTime();
    }

    public function testType()
    {
        $this->assertInstanceOf('Carbon\\Carbon', $this->dateTime);
    }
}
