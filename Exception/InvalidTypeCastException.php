<?php

namespace Tdn\PhpTypes\Exception;

class InvalidTypeCastException extends InvalidTransformationException
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
        $message = $message ?? 'Could not cast %s to %s.';
        parent::__construct($typeFrom, $typeTo, $message, $code, $previous);
    }
}