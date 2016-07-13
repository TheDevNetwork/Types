<?php

namespace Tdn\PhpTypes\Type\Traits;

trait BaseType
{
    /**
     * @var mixed
     */
    protected $value;

    /**
     * @return mixed
     */
    public function get()
    {
        return $this->value;
    }
}
