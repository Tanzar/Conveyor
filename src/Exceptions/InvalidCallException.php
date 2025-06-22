<?php

namespace Tanzar\Conveyor\Exceptions;

use Exception;

class InvalidCallException extends Exception
{

    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
