<?php

declare (strict_types = 1);

namespace Tdn\PhpTypes\Type;

/**
 * Interface NumberTypeInterface.
 */
interface NumberTypeInterface extends TransmutableTypeInterface, ValueTypeInterface
{
    /**
     * Sums current NumberTypeInterface and number passed.
     *
     * @param mixed $num
     *
     * @return NumberTypeInterface
     */
    public function plus($num) : NumberTypeInterface;

    /***
     * Subtracts number passed from current NumberTypeInterface.
     *
     * @param mixed $num
     * @return NumberTypeInterface
     */
    public function minus($num) : NumberTypeInterface;

    /**
     * Multiplies current NumberTypeInterface by the number passed.
     *
     * @param $num
     *
     * @return NumberTypeInterface
     */
    public function multipliedBy($num) : NumberTypeInterface;

    /**
     * Divides current NumberTypeInterface by the number passed.
     *
     * @param mixed $num
     *
     * @return NumberTypeInterface
     */
    public function dividedBy($num) : NumberTypeInterface;

    /**
     * Compares current NumberTypeInterface to value passed.
     * Same rules as spaceship or
     *
     * @param $num
     *
     * @return NumberTypeInterface
     */
    public function compare($num) : NumberTypeInterface;

    /**
     * Returns value of NumberTypeInterface modulus num.
     *
     * @param $num
     *
     * @return NumberTypeInterface
     */
    public function modulus($num) : NumberTypeInterface;

    /**
     * Returns NumberTypeInterface to the power of num.
     *
     * @param $num
     *
     * @return NumberTypeInterface
     */
    public function power($num) : NumberTypeInterface;

    /**
     * Returns the square root of NumberTypeInterface.
     *
     * @return NumberTypeInterface
     */
    public function squareRoot() : NumberTypeInterface;

    /**
     * Returns the absolute value of NumberTypeInterface.
     *
     * @return NumberTypeInterface
     */
    public function absolute() : NumberTypeInterface;

    /**
     * Returns the negated/opposite of NumberTypeInterface value.
     *
     * @return NumberTypeInterface
     */
    public function negate() : NumberTypeInterface;

    /**
     * Returns NumberTypeInterface factorial.
     *
     * @return NumberTypeInterface
     */
    public function factorial() : NumberTypeInterface;

    /**
     * Returns the greatest common divider between NumberTypeInterface and num.
     *
     * @param $num
     *
     * @return NumberTypeInterface
     */
    public function gcd($num) : NumberTypeInterface;

    /**
     * Returns the root of NumberTypeInterface to the num.
     *
     * @param $num
     *
     * @return NumberTypeInterface
     */
    public function root(int $num) : NumberTypeInterface;

    /**
     * Return the next prime number after NumberTypeInterface.
     *
     * @return NumberTypeInterface
     */
    public function getNextPrime() : NumberTypeInterface;

    /**
     * Returns true of NumberTypeInterface is prime. False otherwise.
     *
     * @return BooleanType
     */
    public function isPrime() : BooleanType;

    /**
     * Returns true if NumberTypeInterface is a perfect square. False otherwise.
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
