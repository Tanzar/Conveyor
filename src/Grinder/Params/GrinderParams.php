<?php

namespace Tanzar\Conveyor\Grinder\Params;

use Illuminate\Support\Carbon;
use Tanzar\Conveyor\Exceptions\ConveyorException;
use Tanzar\Conveyor\Models\ConveyorParam;

final class GrinderParams
{
    private array $values = [];
    private array $models = [];

    /**
     * 
     * @param string[] $keys
     */
    public function __construct(private array $keys)
    {
        
    }

    public function string(string $key, string $value): self
    {
        return $this->setValue($key, $value);
    }

    public function int(string $key, int $value): self
    {
        return $this->setValue($key, (string) $value);
    }

    public function float(string $key, float $value): self
    {
        return $this->setValue($key, (string) $value);
    }

    public function date(string $key, Carbon $value, string $format = 'Y-m-d'): self
    {
        return $this->setValue($key, $value->format($format));
    }

    private function setValue(string $key, string $value): self
    {
        if (!in_array($key, $this->keys)) {
            throw new ConveyorException("Param key '$key' is not defined.");
        }

        $model = ConveyorParam::query()
            ->where('name', $key)
            ->where('param_value', $value)
            ->first();

        if (!$model) {
            $model = new ConveyorParam();
            $model->name = $key;
            $model->param_value = $value;
            $model->save();
        }

        $this->models[$key] = $model;
        $this->values[$key] = $value;
        return $this;
    }

    public function getValues(): array
    {
        return $this->values;
    }

    public function getModels(): array
    {
        return $this->models;
    }

    public function copy(): GrinderParams
    {
        $copy = new GrinderParams($this->keys);

        $copy->values = $this->values;
        $copy->models = $this->models;

        return $copy;
    }
}
