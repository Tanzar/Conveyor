<?php

namespace Tanzar\Conveyor\Exceptions;

use Exception;

abstract class ConveyorException extends Exception
{

    public function __construct(string $message = "")
    {
        parent::__construct("Conveyor errror: $message");
    }
}
