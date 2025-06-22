<?php


namespace Tanzar\Conveyor\Exceptions;

use Exception;
use Illuminate\Validation\Validator;

class IncorrectParamOptionsException extends Exception
{

    public function __construct(Validator $validator)
    {
        $msg = 'Incorrect validation for ' . $validator->getMessageBag()->first();
        parent::__construct($msg);
    }
}