<?php

namespace Tanzar\Conveyor\Configs;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use ReflectionFunction;
use Tanzar\Conveyor\Exceptions\InvalidBuildFunctionException;
use Tanzar\Conveyor\Exceptions\InvalidHandlerException;
use Tanzar\Conveyor\Exceptions\InvalidModelException;
use Tanzar\Conveyor\Models\ConveyorModel;
use Tanzar\Conveyor\Params\Params;

class ModelConfig implements ModelConfigInterface
{
    private array $handlers = [];
    private ?Closure $query = null;
    private string $idColumn = 'id';

    public function __construct(private string $class)
    {
        if (!is_subclass_of($class, ConveyorModel::class)) {
            throw new InvalidModelException("Given class $class is not extending " . ConveyorModel::class);
        }
    }

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

    public function idColumn(string $column): ModelConfigInterface
    {
        $this->idColumn = $column;
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

    public function getQuery(Params $params): Builder
    {
        /** @var Builder $query */
        $builder = $this->class::query();

        if ($this->query !== null) {
            $callable = $this->query;
            $callable($builder, $params);
        }

        return $builder;
    }
    
    public function getIdColumn(): string
    {
        return $this->idColumn;
    }
}
