<?php

namespace Tanzar\Conveyor\Exceptions;

use Exception;

class UninitializedConveyorException extends Exception
{

    public function __construct(string $key)
    {
        parent::__construct("Conveyor with key $key was not initialized");
    }
}
