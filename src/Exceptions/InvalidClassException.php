<?php

namespace Tanzar\Conveyor\Exceptions;

final class InvalidClassException extends ConveyorException
{

    public function __construct(string $msg)
    {
        parent::__construct($msg);
    }
}
