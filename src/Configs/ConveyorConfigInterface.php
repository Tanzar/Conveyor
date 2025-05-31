<?php


namespace Tanzar\Conveyor\Configs;

interface ConveyorConfigInterface
{
    public function model(string $class): ModelConfigInterface;
	
}