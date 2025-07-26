<?php


namespace Tanzar\Conveyor\Params;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Tanzar\Conveyor\Exceptions\IncorrectParamOptionsException;
use Tanzar\Conveyor\Helpers\ConveyorUtils;

final class ParamsInitializer
{
	private array $options = [];

    public function __construct(
        private array $rules = []
    ) {

    }

    /**
     * Add option what will generate stream when initiating
     * @param array $option - values that match validator
     * @return ParamsInitializer
     */
    public function option(array $option): self
    {
        $values = $this->checkValidity($option);
        $key = ConveyorUtils::formKey('', $values);

        $this->options[$key] = $values;
        
        return $this;
    }
    
    /**
     * Check if given key value array is valid
     * @param array $option 
     * @throws IncorrectParamOptionsException
     * @return array valid values
     */
    public function checkValidity(array $option): array
    {
        $validator = Validator::make($option, $this->rules);

        if ($validator->fails()) {
            throw new IncorrectParamOptionsException($validator);
        }

        return $validator->validated();
    }

    public function toArray(): array
    {
        $options = collect($this->options);

        if ($options->count() === 0 && count($this->rules) === 0) {
            return [ [] ];
        }

        return $options->values()->toArray();
    }

}