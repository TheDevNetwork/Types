<?php

declare (strict_types = 1);

namespace Tdn\PhpTypes\Exception;

/**
 * Class DivisionByZeroException.
 */
class DivisionByZeroException extends \InvalidArgumentException implements MathExceptionInterface
{
}
