<?php

namespace Tanzar\Conveyor\Exceptions;

use Exception;

class CellValueException extends Exception
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
