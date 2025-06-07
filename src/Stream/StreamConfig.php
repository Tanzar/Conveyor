<?php

namespace Tanzar\Conveyor\Stream;

use Illuminate\Support\Collection;
use Tanzar\Conveyor\Exceptions\InvalidModelException;
use Tanzar\Conveyor\Models\ConveyorModel;

class StreamConfig
{
    private Collection $handlers;


    public function __construct()
    {
        $this->handlers = collect();
    }

    public function add(string $model, callable $handler): self
    {
        if (!is_subclass_of($model, ConveyorModel::class)) {
            throw new InvalidModelException(
                'Given model is not correct, make sure your model is extending ' . ConveyorModel::class
            );
        }

        if ($this->handlers->has($model)) {
            $handlers = $this->handlers->get($model);
            $handlers[] = $handler;
        } else {
            $handlers = [ $handler ];
        }

        $this->handlers->put($model, $handlers);

        return $this;
    }

    /**
     * Array of classes set in config
     * @return string[]
     */
    public function models(): array
    {
        return $this->handlers->keys()->toArray();
    }

    /**
     * Array of callable handlers from config
     * @param string $model
     * @return callable[]
     */
    public function handlers(string $model): array
    {
        return $this->handlers->get($model, []);
    }
}
