<?php

namespace Tanzar\Conveyor\Stream\Exceptions;

use Exception;

class InvalidModelException extends Exception
{

    public function __construct(string $msg)
    {
        parent::__construct($msg);
    }
}
