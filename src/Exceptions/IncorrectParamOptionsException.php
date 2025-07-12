<?php


namespace Tanzar\Conveyor\Exceptions;

use Illuminate\Validation\Validator;

final class IncorrectParamOptionsException extends ConveyorException
{

    public function __construct(Validator $validator)
    {
        $msg = 'Incorrect validation for ' . $validator->getMessageBag()->first();
        parent::__construct($msg);
    }
}