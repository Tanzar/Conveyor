<?php

namespace Tanzar\Conveyor\Configs;

use Illuminate\Database\Eloquent\Model;

final class ConveyorConfig implements ConveyorConfigInterface
{
    private array $models = [];

    /**
     * Adds new model to config
     * @param string $class - class name, must extend Tanzar\Conveyor\Models\ConveyorModel
     * @return ModelConfigInterface
     */
    public function model(string $class): ModelConfigInterface
    {
        $config = new ModelConfig($class);

        $this->models[$class] = $config;

        return $config;
    }

    /**
     * Get config by model class name
     * @param string $class
     */
    public function get(string $class): ?ModelConfig
    {
        return $this->models[$class] ?? null;
    }

    /**
     * 
     * @return ModelConfig[]
     */
    public function toArray(): array
    {
        return $this->models;
    }
}
