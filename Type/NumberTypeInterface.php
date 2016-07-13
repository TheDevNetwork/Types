<?php

declare (strict_types = 1);

namespace Tdn\PhpTypes\Type;

/**
 * Interface NumberTypeInterface.
 */
interface NumberTypeInterface extends TransmutableTypeInterface, ValueInterface
{
    /**
     * Increases current NunmberType by the number passed.
     *
     * @param mixed $num
     *
     * @return NumberTypeInterface
     */
    public function plus($num) : NumberTypeInterface;

    /***
     * Decreases current NunmberType by the number passed.
     *
     * @param mixed $num
     * @return NumberTypeInterface
     */
    public function minus($num) : NumberTypeInterface;

    /**
     * Multiplies current NunmberType by the number passed.
     *
     * @param $num
     *
     * @return NumberTypeInterface
     */
    public function multiplyBy($num) : NumberTypeInterface;

    /**
     * Divides current NunmberType by the number passed.
     *
     * @param mixed $num
     *
     * @return NumberTypeInterface
     */
    public function divideBy($num) : NumberTypeInterface;

    /**
     * Compares current NumberType to value passed.
     * Returns as follows:
     * NumberType > num = 1
     * NumberType = num = 0
     * NumberType < num = -1.
     *
     * @param $num
     *
     * @return NumberTypeInterface
     */
    public function compare($num) : NumberTypeInterface;

    /**
     * Returns value of NumberType modulus num.
     *
     * @param $num
     *
     * @return NumberTypeInterface
     */
    public function modulus($num) : NumberTypeInterface;

    /**
     * Returns NumberType to the power of num.
     *
     * @param $num
     *
     * @return NumberTypeInterface
     */
    public function power($num) : NumberTypeInterface;

    /**
     * Returns the square root of NumberType.
     *
     * @return NumberTypeInterface
     */
    public function squareRoot() : NumberTypeInterface;

    /**
     * Returns the absolute value of NumberType.
     *
     * @return NumberTypeInterface
     */
    public function absolute() : NumberTypeInterface;

    /**
     * Returns the negated version of NumberType. (Similar to ABS, but will always return the opposite).
     *
     * @return NumberTypeInterface
     */
    public function negate() : NumberTypeInterface;

    /**
     * Returns NumberType factorial.
     *
     * @return NumberTypeInterface
     */
    public function factorial() : NumberTypeInterface;

    /**
     * Returns the greatest common divider between NumberType and num.
     *
     * @param $num
     *
     * @return NumberTypeInterface
     */
    public function gcd($num) : NumberTypeInterface;

    /**
     * Returns the root of NumberType to the num.
     *
     * @param $num
     *
     * @return NumberTypeInterface
     */
    public function root(int $num) : NumberTypeInterface;

    /**
     * Return the next prime number after NumberType.
     *
     * @return NumberTypeInterface
     */
    public function getNextPrime() : NumberTypeInterface;

    /**
     * Returns true of NumberType is prime. False otherwise.
     *
     * @return BooleanType
     */
    public function isPrime() : BooleanType;

    /**
     * Returns true if NumberType is a perfect square. False otherwise.
     *
     * @return BooleanType
     */
    public function isPerfectSquare() : BooleanType;

    /**
     * Gets the current precision (Should be 0 for IntType).
     *
     * @return int
     */
    public function getPrecision() : int;

    /**
     * Creates a new instance of NumberTypeInterface from the variable passed.
     *
     * @param $mixed
     * @param int|null $precision
     *
     * @return NumberTypeInterface
     */
    public static function valueOf($mixed, int $precision = null);
}
