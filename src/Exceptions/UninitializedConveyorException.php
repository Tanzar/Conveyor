<?php

namespace Tanzar\Conveyor\Exceptions;

final class UninitializedConveyorException extends ConveyorException
{

    public function __construct(string $key)
    {
        parent::__construct("Conveyor with key $key was not initialized");
    }
}
