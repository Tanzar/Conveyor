<?php


namespace Tanzar\Conveyor\Params;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Tanzar\Conveyor\Exceptions\IncorrectParamOptionsException;

final class OptionsInitializer
{
	private array $options = [];

    public function __construct(
        private array $rules
    ) {

    }

    /**
     * Add option what will generate stream when initiating
     * @param array $option - values that match validator
     * @param bool $throwErrors - disable error throwing
     * @throws IncorrectParamOptionsException
     * @return OptionsInitializer
     */
    public function option(array $option, bool $throwErrors = true): self
    {
        $validator = Validator::make($option, $this->rules);

        if ($validator->fails()) {
            if ($throwErrors) {
                throw new IncorrectParamOptionsException($validator);
            }
        } else {
            $values = $validator->validated();
            $key = self::formKey($values);

            $this->options[$key] = $values;
        }
        
        return $this;
    }
    
    /**
     * Form key from params array
     * @param string[] $values
     * @return string
     */
    public static function formKey(array $values): string
    {
        $text = '';
        foreach ($values as $key => $value) {
            if ($value instanceof Carbon) {
                $value = $value->format('Y-m-d-H-i-s');
            }
            $text .= "$key=$value;";
        }
        return $text;
    }

    public function toArray(): array
    {
        return collect($this->options)
            ->values()
            ->toArray();
    }
}