<?php

namespace Tanzar\Conveyor\Stream;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Tanzar\Conveyor\Exceptions\InvalidClassException;

class StreamConfig
{
    private Collection $handlers;


    public function __construct()
    {
        $this->handlers = collect();
    }

    public function add(string $model, callable $handler): self
    {
        if (!is_subclass_of($model, Model::class)) {
            throw new InvalidClassException(
                'Given class is not correct, make sure your class is extending ' . Model::class
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
