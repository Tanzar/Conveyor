<?php

namespace Tanzar\Conveyor\Exceptions;

final class CellLockedException extends ConveyorException
{
    public function __construct()
    {
        parent::__construct('Call to locked cell, to prevent looping change your logic');
    }
}
