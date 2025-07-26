<?php

namespace Tanzar\Conveyor\Exceptions;

class UnauthorizedAccessException extends ConveyorException
{

    public function __construct(string $class)
    {
        parent::__construct("Access denied to $class");
    }
}
