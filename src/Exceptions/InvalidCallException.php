<?php

namespace Tanzar\Conveyor\Exceptions;

final class InvalidCallException extends ConveyorException
{

    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
