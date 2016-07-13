<?php

declare (strict_types = 1);

namespace Tdn\PhpTypes\Type;

use Tdn\PhpTypes\Exception\InvalidTransformationException;
use Tdn\PhpTypes\Math\DefaultMathAdapter;
use Tdn\PhpTypes\Math\MathAdapterInterface;
use Tdn\PhpTypes\Type\Traits\ValueType;
use Tdn\PhpTypes\Type\Traits\Boxable;
use Tdn\PhpTypes\Type\Traits\Transmutable;

/**
 * Class AbstractNumberType.
 */
abstract class AbstractNumberType implements NumberTypeInterface
{
    use ValueType;
    use Transmutable;
    use Boxable;

    /**
     * @var int
     */
    private $precision;

    /**
     * @var number
     */
    protected $value;

    /**
     * @var MathAdapterInterface
     */
    protected $mathAdapter;

    /**
     * AbstractNumberType constructor.
     * Precision order of priority: Argument != null > $num's precision > null precision.
     * So for an int, 0 should be passed for precision, otherwise it will auto-convert to float (if null or $num > 0).
     *
     * @param number                    $num
     * @param int|null                  $precision
     * @param MathAdapterInterface|null $mathAdapter
     */
    public function __construct($num, int $precision = null, MathAdapterInterface $mathAdapter = null)
    {
        $this->mathAdapter = $mathAdapter ?? new DefaultMathAdapter();
        $this->precision = $precision ?? $this->getMathAdapter()->getPrecision($num);
        $this->value = ($this->getPrecision() > 0) ?
            round($num, $this->getPrecision(), $this->mathAdapter->getRoundingStrategy()) : $num;
    }

    /**
     * Sums current NumberTypeInterface and number in argument.
     *
     * @param NumberTypeInterface|number|StringType|string $num
     *
     * @return NumberTypeInterface
     */
    public function plus($num) : NumberTypeInterface
    {
        return $this->getAdapterOperation('add', $num);
    }

    /***
     * Subtracts number passed from current NumberTypeInterface.
     *
     * @param NumberTypeInterface|number|StringType|string $num
     *
     * @return NumberTypeInterface
     */
    public function minus($num) : NumberTypeInterface
    {
        return $this->getAdapterOperation('subtract', $num);
    }

    /**
     * Multiplies current NumberTypeInterface by the number passed.
     *
     * @param NumberTypeInterface|number|StringType|string $num
     *
     * @return NumberTypeInterface
     */
    public function multipliedBy($num) : NumberTypeInterface
    {
        return $this->getAdapterOperation('multiply', $num);
    }

    /**
     * Divides current NumberTypeInterface by the number passed.
     *
     * @param NumberTypeInterface|number|StringType|string $num
     *
     * @return NumberTypeInterface
     */
    public function dividedBy($num) : NumberTypeInterface
    {
        return $this->getAdapterOperation('divide', $num);
    }

    /**
     * Compares current NumberTypeInterface to value passed.
     * Same rules as spaceship or version_compare.
     *
     * @param NumberTypeInterface|number|StringType|string $num
     *
     * @return NumberTypeInterface
     */
    public function compare($num) : NumberTypeInterface
    {
        return $this->getAdapterOperation(__FUNCTION__, $num);
    }

    /**
     * Returns value of NumberTypeInterface modulus num.
     *
     * @param NumberTypeInterface|number|StringType|string $num
     *
     * @return NumberTypeInterface
     */
    public function modulus($num) : NumberTypeInterface
    {
        return $this->getAdapterOperation(__FUNCTION__, $num);
    }

    /**
     * Returns NumberTypeInterface to the power of num.
     *
     * @param NumberTypeInterface|number|StringType|string $num
     *
     * @return NumberTypeInterface
     */
    public function power($num) : NumberTypeInterface
    {
        return $this->getAdapterOperation(__FUNCTION__, $num);
    }

    /**
     * Returns the square root of NumberTypeInterface.
     *
     * @return NumberTypeInterface
     */
    public function squareRoot() : NumberTypeInterface
    {
        return static::valueOf(
            $this->getMathAdapter()->squareRoot(
                $this->toStringType()->get(),
                $this->getPrecision()
            ),
            $this->getPrecision()
        );
    }

    /**
     * Returns the absolute value of NumberTypeInterface.
     *
     * @return NumberTypeInterface
     */
    public function absolute() : NumberTypeInterface
    {
        return static::valueOf(
            $this->getMathAdapter()->absolute($this->toStringType()->get()),
            $this->getPrecision()
        );
    }

    /**
     * Returns the negated/opposite of NumberTypeInterface value.
     *
     * @return NumberTypeInterface
     */
    public function negate() : NumberTypeInterface
    {
        return static::valueOf(
            $this->getMathAdapter()->negate($this->toStringType()->get()),
            $this->getPrecision()
        );
    }

    /**
     * Returns NumberTypeInterface factorial.
     *
     * @return NumberTypeInterface
     */
    public function factorial() : NumberTypeInterface
    {
        return static::valueOf(
            $this->getMathAdapter()->factorial($this->toStringType()->get()),
            $this->getPrecision()
        );
    }

    /**
     * Returns the greatest common divider between NumberTypeInterface and num.
     *
     * @param NumberTypeInterface|number|StringType|string $num
     *
     * @return NumberTypeInterface
     */
    public function gcd($num) : NumberTypeInterface
    {
        return $this->getAdapterOperation(__FUNCTION__, $num);
    }

    /**
     * Returns the root of NumberTypeInterface to the num.
     *
     * @param int $num
     *
     * @return NumberTypeInterface
     */
    public function root(int $num) : NumberTypeInterface
    {
        return static::valueOf(
            $this->getMathAdapter()->root($this->toStringType()->get(), $num),
            $this->getPrecision()
        );
    }

    /**
     * Return the next prime number after NumberTypeInterface.
     *
     * @return NumberTypeInterface
     */
    public function getNextPrime() : NumberTypeInterface
    {
        return static::valueOf($this->getMathAdapter()->nextPrime($this->toStringType()->get()));
    }

    /**
     * Returns true of NumberTypeInterface is prime. False otherwise.
     *
     * @return BooleanType
     */
    public function isPrime() : BooleanType
    {
        return new BooleanType($this->getMathAdapter()->isPrime($this->toStringType()->get()));
    }

    /**
     * Returns true if NumberTypeInterface is a perfect square. False otherwise.
     *
     * @return BooleanType
     */
    public function isPerfectSquare() : BooleanType
    {
        return new BooleanType($this->getMathAdapter()->isPerfectSquare($this->toStringType()->get()));
    }

    /**
     * Gets the current precision (Should be 0 for IntType).
     *
     * @return int
     */
    public function getPrecision() : int
    {
        return $this->precision;
    }

    /**
     * @param callable $converterFunction
     * @param $mixed
     *
     * @return mixed
     */
    protected static function asSubType(callable $converterFunction, $mixed)
    {
        if ($mixed instanceof StringType || $mixed instanceof NumberTypeInterface) {
            $mixed = $mixed->get(); //Continue as primitive.
        }

        $type = strtolower(gettype($mixed));
        switch ($type) {
            case 'integer':
            case 'double':
            case 'float':
                return $converterFunction($mixed);
            case 'string':
                if (!is_numeric($mixed)) {
                    throw new InvalidTransformationException($type, static::class);
                }

                return $converterFunction($mixed);
            default:
                throw new InvalidTransformationException($type, static::class);
        }
    }

    /**
     * @return MathAdapterInterface
     */
    protected function getMathAdapter()
    {
        return $this->mathAdapter;
    }

    /**
     * @param string $operation
     * @param $operand
     *
     * @return NumberTypeInterface
     */
    private function getAdapterOperation(string $operation, $operand) : NumberTypeInterface
    {
        if (!is_callable([$this->getMathAdapter(), $operation])) {
            throw new \LogicException(
                sprintf(
                    'Operation does not exist. Invalid operation: %s::%s()',
                    get_class($this->getMathAdapter()),
                    $operation
                )
            );
        }

        return static::valueOf(
            $this->getMathAdapter()->$operation(
                $this->toStringType()->get(),
                static::valueOf($operand)->toStringType()->get(),
                $this->getPrecision()
            ),
            $this->getPrecision()
        );
    }
}
