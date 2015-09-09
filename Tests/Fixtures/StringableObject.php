<?php

namespace Tdn\PhpTypes\Tests\Fixtures;

/**
 * Class StringableObject
 * @package Tdn\PhpTypes\Tests\Fixtures
 */
class StringableObject
{
    /**
     * @return string
     */
    public function __toString()
    {
        return 'foo';
    }
}
