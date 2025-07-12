<?php

namespace Tanzar\Conveyor\Exceptions;

final class CellValueException extends ConveyorException
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
