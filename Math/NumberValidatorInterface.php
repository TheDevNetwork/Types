<?php

declare(strict_types=1);

namespace Tdn\PhpTypes\Math;

/**
 * Interface NumberValidatorInterface.
 */
interface NumberValidatorInterface
{
    /**
     * Ensures scalar variable is a number.
     *
     * @param mixed $number
     *
     * @return bool
     */
    public function isValid($number): bool;
}
