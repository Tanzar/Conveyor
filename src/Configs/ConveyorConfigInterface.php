<?php


namespace Tanzar\Conveyor\Configs;

interface ConveyorConfigInterface
{
    /**
     * Adds new model to config
     * @param string $class - class name, must extend Tanzar\Conveyor\Models\ConveyorModel
     * @return ModelConfigInterface
     */
    public function model(string $class): ModelConfigInterface;
	
}