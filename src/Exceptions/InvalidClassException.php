<?php

namespace Tanzar\Conveyor\Exceptions;

use Exception;

class InvalidClassException extends Exception
{

    public function __construct(string $msg)
    {
        parent::__construct($msg);
    }
}
