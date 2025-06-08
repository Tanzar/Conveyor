<?php

namespace Tanzar\Conveyor\Configs;

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
     * 
     * @return ModelConfig[]
     */
    public function toArray(): array
    {
        return $this->models;
    }
}
