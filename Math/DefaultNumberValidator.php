<?php

declare(strict_types=1);

namespace Tdn\PhpTypes\Math;

/**
 * Class DefaultNumberValidator.
 */
class DefaultNumberValidator implements NumberValidatorInterface
{
    /**
     * Ensures scalar variable is a number.
     *
     * @param mixed $number
     *
     * @return bool
     */
    public function isValid($number): bool
    {
        return is_scalar($number) && is_numeric($number);
    }
}
