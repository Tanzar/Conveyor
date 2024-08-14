<?php

namespace Tanzar\Conveyor\Base\Exceptions;
use Exception;

class CellLockedException extends Exception
{
    public function __construct()
    {
        parent::__construct('Call to locked cell, to prevent looping change your logic');
    }
}
