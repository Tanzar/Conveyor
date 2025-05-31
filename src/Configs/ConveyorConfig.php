<?php

namespace Tanzar\Conveyor\Configs;

use Tanzar\Conveyor\Exceptions\InvalidModelException;
use Tanzar\Conveyor\Models\ConveyorCacheableModel;

final class ConveyorConfig implements ConveyorConfigInterface
{
    private array $models = [];

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
