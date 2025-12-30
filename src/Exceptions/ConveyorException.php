<?php

namespace Tanzar\Conveyor\Exceptions;

use Exception;
use Throwable;

class ConveyorException extends Exception
{
    protected string $prefix = 'Conveyor general error: ';

    public function __construct(string $message)
    {
        return parent::__construct($this->prefix . $message);
    }
}
