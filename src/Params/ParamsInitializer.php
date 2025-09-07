<?php


namespace Tanzar\Conveyor\Params;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Tanzar\Conveyor\Exceptions\IncorrectParamOptionsException;

final class ParamsInitializer
{
	private array $options = [];

    /**
     * 
     * @param array $rules
     * @param string[] $ignoreForKey
     */
    public function __construct(
        private string $baseKey,
        private array $rules = [],
        private array $ignoreForKey = [],
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
        $key = $this->formKey($values);

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

    
    /**
     * Form key from params array
     * @param string[] $values
     * @return string
     */
    public function formKey(array $values): string
    {
        $text = "$this->baseKey-";
        foreach ($values as $key => $value) {

            if (in_array($key, $this->ignoreForKey)) {
                continue;
            }

            if ($value instanceof Carbon) {
                $value = $value->format('Y-m-d-H-i-s');
            }
            $text .= "$key=$value;";
        }
        return $text;
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