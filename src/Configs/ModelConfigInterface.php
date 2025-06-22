<?php

namespace Tanzar\Conveyor\Configs;
use Tanzar\Conveyor\Exceptions\InvalidBuildFunctionException;
use Tanzar\Conveyor\Exceptions\InvalidHandlerException;

interface ModelConfigInterface
{

    /**
     * Add handler to manage model when updating conveyor
     * $handler needs one parameter with model class typehint
     * @param callable $handler function called to manage model when updating conveyor
     * @throws InvalidHandlerException
     * @return ModelConfigInterface
     */
    public function handler(callable $handler): self;

    /**
     * Add query to retrieve models when recalculating or initializing conveyor
     * $build uses 2 parameters:
     *  1) Laravels query builder
     *  2) Tanzar\Conveyor\Params\Params, which will be passed into method by convayor to use parameters for specified conveyor
     * @param callable $build - function called to build query
     * @throws InvalidBuildFunctionException
     * @return ModelConfigInterface
     */
    public function query(callable $build): self;

    /**
     * Add relation to be loaded
     * @param string[]|string $relation
     * @return ModelConfigInterface
     */
    public function relation(string|array $relation): self;
}
