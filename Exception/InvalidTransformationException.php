<?php

namespace Tdn\PhpTypes\Exception;

/**
 * Class InvalidTransformationException.
 */
class InvalidTransformationException extends \RuntimeException
{
    /**
     * @param string          $typeFrom
     * @param string          $typeTo
     * @param string|null     $message
     * @param int             $code
     * @param \Exception|null $previous
     */
    public function __construct(
        string $typeFrom,
        string $typeTo,
        string $message = null,
        int $code = 0,
        \Exception $previous = null
    ) {
        $typeFrom = ($typeFrom === 'double') ? 'float' : $typeFrom;
        $message = sprintf(
            $message ?? 'Could not transform %s to %s.',
            $this->getWithoutNamespace($typeFrom),
            $this->getWithoutNamespace($typeTo)
        );

        parent::__construct($message, $code, $previous);
    }

    /**
     * @param string $fullyQualified
     *
     * @return string
     */
    private function getWithoutNamespace(string $fullyQualified) : string
    {
        if (class_exists($fullyQualified)) {
            $c = (new \ReflectionClass($fullyQualified))->getShortName();

            return $c;
        }

        return $fullyQualified;
    }
}
