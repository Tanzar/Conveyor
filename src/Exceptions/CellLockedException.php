<?php

namespace Tanzar\Conveyor\Exceptions;
use Exception;

class CellLockedException extends Exception
{
    public function __construct()
    {
        parent::__construct('Call to locked cell, to prevent looping change your logic');
    }
}
