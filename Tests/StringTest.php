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
    const LOREM_IPSUM     =
        'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt.';

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
            (string) String::create(self::LOREM_IPSUM)->subStrUntil(',', true)
        );
        $this->assertEquals(
            'Lorem ipsum dolor sit amet,',
            (string) String::create(self::LOREM_IPSUM)->subStrUntil(',')
        );
    }

    public function testSubStrAfter()
    {
        $this->assertEquals(
            'sed do eiusmod tempor incididunt.',
            (string) String::create(self::LOREM_IPSUM)->subStrAfter('elit, ', true)
        );
        $this->assertEquals(
            'elit, sed do eiusmod tempor incididunt.',
            (string) String::create(self::LOREM_IPSUM)->subStrAfter('elit, ')
        );
    }

    public function testSubStrBetween()
    {
        $this->assertEquals(
            'consectetur adipiscing elit',
            (string) String::create(self::LOREM_IPSUM)->subStrBetween('amet, ', ', sed', true, true)
        );
        $this->assertEquals(
            'amet, consectetur adipiscing elit',
            (string) String::create(self::LOREM_IPSUM)->subStrBetween('amet, ', ', sed', false, true)
        );
        $this->assertEquals(
            'consectetur adipiscing elit, sed',
            (string) String::create(self::LOREM_IPSUM)->subStrBetween('amet, ', ', sed', true, false)
        );
        $this->assertEquals(
            'amet, consectetur adipiscing elit, sed',
            (string) String::create(self::LOREM_IPSUM)->subStrBetween('amet, ', ', sed')
        );
    }

    public function testStrpos()
    {
        $this->assertEquals(6, String::create(self::LOREM_IPSUM)->strpos('ipsum'));
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
        $str = "\n" . '    test';
        $this->assertEquals(4, (int) String::create($str)->getPadSize());

        $str = "\n" . '    test' . "\n";
        $this->assertEquals(4, (int) String::create($str)->getPadSize());

        $str = "\n" .
            "    test2L1\n" .
            "    test2L2\n" .
            "    test3L3\n"
        ;

        $this->assertEquals(4, (int) String::create($str)->getPadSize());
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testIndentSizeFail()
    {
        $str = "\n" .
            "   test2L1\n" .
            "    test2L2\n" .
            "    test3L3\n"
        ;

        $this->assertEquals(4, (int) String::create($str)->getPadSize());
    }
}
