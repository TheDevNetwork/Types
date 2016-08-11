<?php

namespace Tdn\PhpTypes\Type\Traits;

trait ValueType
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
