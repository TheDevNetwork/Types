<?php

namespace Tdn\PhpTypes\Tests;

use Tdn\PhpTypes\Type\String;

/**
 * Class String
 * @package Tdn\PhpTypes\Tests
 */
class StringTest extends \PHPUnit_Framework_TestCase
{
    const STRING_SINGULAR = "syllabus";
    const STRING_PLURAL   = "syllabi";
    const LOREM_IPSUM     = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.';

    public function testSingular()
    {
        $this->assertEquals((string) (new String(self::STRING_PLURAL))->singularize(), self::STRING_SINGULAR);
    }

    public function testPluralize()
    {
        $this->assertEquals((string) (new String(self::STRING_SINGULAR))->pluralize(), self::STRING_PLURAL);
    }

    public function testSubStrUntil()
    {
        $this->assertEquals(
            'Lorem ipsum dolor sit amet',
            (string) String::create($this->loremIpsum)->subStrUntil(',', true)
        );
        $this->assertEquals(
            'Lorem ipsum dolor sit amet,',
            (string) String::create($this->loremIpsum)->subStrUntil(',')
        );
    }

    public function testSubStrAfter()
    {
        $this->assertEquals(
            'sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
            (string) String::create($this->loremIpsum)->subStrAfter('elit, ', true)
        );
        $this->assertEquals(
            'elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
            (string) String::create($this->loremIpsum)->subStrAfter('elit, ')
        );
    }

    public function testSubStrFromTo()
    {
        $this->assertEquals(
            'consectetur adipiscing elit',
            (string) String::create($this->loremIpsum)->subStrBetween('amet, ', ', sed', true, true)
        );
        $this->assertEquals(
            'amet, consectetur adipiscing elit',
            (string) String::create($this->loremIpsum)->subStrBetween('amet, ', ', sed', false, true)
        );
        $this->assertEquals(
            'consectetur adipiscing elit, sed',
            (string) String::create($this->loremIpsum)->subStrBetween('amet, ', ', sed', true, false)
        );
        $this->assertEquals(
            'amet, consectetur adipiscing elit, sed',
            (string) String::create($this->loremIpsum)->subStrBetween('amet, ', ', sed')
        );
    }

    public function testStrpos()
    {
        $this->assertEquals(6, String::create($this->loremIpsum)->strpos('ipsum'));
    }

    public function testAddIndent()
    {
        $str = "    Hello world!" . "\n" . "    Foo bar!";
        $this->assertEquals($str, (string) String::create("Hello world!\nFoo bar!")->addIndent(4, null, true));
        $str = "    Foo Test.";
        $this->assertEquals($str, (string) String::create("Foo Test.")->addIndent());
    }

    public function testIndentSize()
    {
        $str = "\n" . '    $test';
        $this->assertEquals(4, (int) String::create($str)->getIndentSize());
    }
}
