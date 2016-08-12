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
     *
     * @param $num
     * @param int                       $precision
     * @param MathAdapterInterface|null $mathAdapter
     */
    public function __construct($num, $precision = 0, MathAdapterInterface $mathAdapter = null)
    {
        $this->mathAdapter = $mathAdapter ?? new DefaultMathAdapter();
        $this->precision = $precision ?? $this->getMathAdapter()->getPrecision($num);
        $this->value = ($this->getPrecision() > 0) ? round($num, $this->getPrecision()) : $num;
    }

    /**
     * {@inheritDoc}
     */
    public function plus($num) : NumberTypeInterface
    {
        return static::valueOf(
            $this->getMathAdapter()->add(
                $this->toString()->get(),
                static::valueOf($num)->toString()->get(),
                $this->getPrecision()
            ),
            $this->getPrecision()
        );
    }

    /**
     * {@inheritDoc}
     */
    public function minus($num) : NumberTypeInterface
    {
        return static::valueOf(
            $this->getMathAdapter()->subtract(
                $this->toString()->get(),
                static::valueOf($num)->toString()->get(),
                $this->getPrecision()
            ),
            $this->getPrecision()
        );
    }

    /**
     * {@inheritDoc}
     */
    public function multipliedBy($num) : NumberTypeInterface
    {
        return static::valueOf(
            $this->getMathAdapter()->multiply(
                $this->toString()->get(),
                static::valueOf($num)->toString()->get(),
                $this->getPrecision()
            ),
            $this->getPrecision()
        );
    }

    /**
     * {@inheritDoc}
     */
    public function dividedBy($num) : NumberTypeInterface
    {
        return static::valueOf(
            $this->getMathAdapter()->divide(
                $this->toString()->get(),
                static::valueOf($num)->toString()->get(),
                $this->getPrecision()
            ),
            $this->getPrecision()
        );
    }

    /**
     * {@inheritDoc}
     */
    public function compare($num) : NumberTypeInterface
    {
        return static::valueOf(
            $this->getMathAdapter()->compare(
                $this->toString()->get(),
                static::valueOf($num)->toString()->get(),
                $this->getPrecision()
            ),
            $this->getPrecision()
        );
    }

    /**
     * {@inheritDoc}
     */
    public function modulus($num) : NumberTypeInterface
    {
        return static::valueOf(
            $this->getMathAdapter()->modulus(
                $this->toString()->get(),
                static::valueOf($num)->toString()->get(),
                $this->getPrecision()
            ),
            $this->getPrecision()
        );
    }

    /**
     * {@inheritDoc}
     */
    public function power($num) : NumberTypeInterface
    {
        return static::valueOf(
            $this->getMathAdapter()->power(
                $this->toString()->get(),
                static::valueOf($num)->toString()->get(),
                $this->getPrecision()
            ),
            $this->getPrecision()
        );
    }

    /**
     * {@inheritDoc}
     */
    public function squareRoot() : NumberTypeInterface
    {
        return static::valueOf(
            $this->getMathAdapter()->squareRoot(
                $this->toString()->get(),
                $this->getPrecision()
            ),
            $this->getPrecision()
        );
    }

    /**
     * {@inheritDoc}
     */
    public function absolute() : NumberTypeInterface
    {
        return static::valueOf(
            $this->getMathAdapter()->absolute($this->toString()->get()),
            $this->getPrecision()
        );
    }

    /**
     * {@inheritDoc}
     */
    public function negate() : NumberTypeInterface
    {
        return static::valueOf(
            $this->getMathAdapter()->negate($this->toString()->get()),
            $this->getPrecision()
        );
    }

    /**
     * {@inheritDoc}
     */
    public function factorial() : NumberTypeInterface
    {
        return static::valueOf(
            $this->getMathAdapter()->factorial($this->toString()->get()),
            $this->getPrecision()
        );
    }

    /**
     * {@inheritDoc}
     */
    public function gcd($num) : NumberTypeInterface
    {
        return static::valueOf(
            $this->getMathAdapter()->gcd(
                $this->toString()->get(),
                static::valueOf($num)->toString()->get()
            ),
            $this->getPrecision()
        );
    }

    /**
     * {@inheritDoc}
     */
    public function root(int $num) : NumberTypeInterface
    {
        return static::valueOf(
            $this->getMathAdapter()->root($this->toString()->get(), $num),
            $this->getPrecision()
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getNextPrime() : NumberTypeInterface
    {
        return static::valueOf($this->getMathAdapter()->nextPrime($this->toString()->get()));
    }

    /**
     * {@inheritDoc}
     */
    public function isPrime() : BooleanType
    {
        return new BooleanType($this->getMathAdapter()->isPrime($this->toString()->get()));
    }

    /**
     * {@inheritDoc}
     */
    public function isPerfectSquare() : BooleanType
    {
        return new BooleanType($this->getMathAdapter()->isPerfectSquare($this->toString()->get()));
    }

    /**
     * {@inheritDoc}
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
        if ($mixed instanceof StringType || $mixed instanceof self) {
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
}
