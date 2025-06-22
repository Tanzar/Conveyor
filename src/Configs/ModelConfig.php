<?php

namespace Tanzar\Conveyor\Configs;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use ReflectionFunction;
use Tanzar\Conveyor\Exceptions\InvalidBuildFunctionException;
use Tanzar\Conveyor\Exceptions\InvalidHandlerException;
use Tanzar\Conveyor\Exceptions\InvalidClassException;
use Tanzar\Conveyor\Params\Params;

class ModelConfig implements ModelConfigInterface
{
    private array $handlers = [];
    private ?Closure $query = null;
    private array $relations = [];

    public function __construct(private string $class)
    {
        if (!is_subclass_of($class, Model::class)) {
            throw new InvalidClassException("Given class $class is not extending " . Model::class);
        }
    }

    /**
     * Add handler to manage model when updating conveyor
     * $handler needs one parameter with model class typehint
     * @param callable $handler function called to manage model when updating conveyor
     * @throws InvalidHandlerException
     * @return ModelConfigInterface
     */
    public function handler(callable $handler): ModelConfigInterface
    {
        $message = $this->checkHandlerFunction($handler);
        if ($message) {
            throw new InvalidHandlerException($message);
        }
        $this->handlers[] = $handler;
        return $this;
    }

    private function checkHandlerFunction(callable $handler): ?string
    {
        $reflect = new ReflectionFunction($handler);
        $params = $reflect->getParameters();
        if (count($params) != 1) {
            return'Invalid handler, only one parameter is passed to handler.';
        }
        $type = $params[0]->getType()?->getName();
        if (is_subclass_of($type, $this->class)) {
            return "Invalid handler, parameter type must extend $this->class";
        }
        return null;
    }
    
    /**
     * Add query to retrieve models when recalculating or initializing conveyor
     * $build uses 2 parameters:
     *  1) Laravels query builder
     *  2) Tanzar\Conveyor\Params\Params, which will be passed into method by convayor to use parameters for specified conveyor
     * @param callable $build - function called to build query
     * @throws InvalidBuildFunctionException
     * @return ModelConfigInterface
     */
    public function query(callable $build): ModelConfigInterface
    {
        $message = $this->checkBuildFunction($build);
        if ($message) {
            throw new InvalidBuildFunctionException($message);
        }

        $this->query = Closure::fromCallable($build);

        return $this;
    }

    private function checkBuildFunction(callable $build): ?string
    {
        $reflect = new ReflectionFunction($build);
        $params = $reflect->getParameters();
        if (count($params) != 2) {
            return'Invalid build function, two parameters are passed to build function.';
        }

        $typeFirst = $params[0]->getType()?->getName();
        if ($typeFirst !== Builder::class) {
            return 'Invalid build function, first parameter type must be ' . Builder::class . ", type $typeFirst given.";
        }

        $typeSecond = $params[1]->getType()?->getName();
        if ($typeSecond !== Params::class) {
            return 'Invalid build function, second parameter type must be ' . Params::class . ", type $typeSecond given.";
        }

        return null;
    }

    /**
     * Add relation to be loaded
     * @param string[]|string $relation
     * @return ModelConfigInterface
     */
    public function relation(array|string $relation): ModelConfigInterface
    {
        if (is_array($relation)) {
            foreach ($relation as $text) {
                if (is_string($text)) {
                    $this->relations[] = $text;
                }
            }
        } else {
            $this->relations[] = $relation;
        }
        return $this;
    }

    /**
     * 
     * @return callable[]
     */
    public function getHandlers(): array
    {
        return $this->handlers;
    }

    public function getQuery(Params $params, bool $withRelations = false): Builder
    {
        /** @var Builder $query */
        $builder = $this->class::query();

        if ($withRelations && count($this->relations) > 0) {
            $builder->with($this->relations);
        }

        if ($this->query !== null) {
            $callable = $this->query;
            $callable($builder, $params);
        }

        return $builder;
    }
    
    public function getIdColumn(): string
    {
        $class = $this->class;

        /** @var Model $model */
        $model = new $class();
        return $model->getKeyName();
    }

    /**
     * @return string[]
     */
    public function getRelations(): array
    {
        return $this->relations;
    }
}
