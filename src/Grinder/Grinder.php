<?php

namespace Tanzar\Conveyor\Grinder;

use Illuminate\Database\Eloquent\Model;
use Tanzar\Conveyor\Exceptions\ConveyorException;
use Tanzar\Conveyor\Models\ConveyorGrinderKey;

abstract class Grinder
{
    private ConveyorGrinderKey $key;

    private GrinderSetup $config;


    public function __construct(string $key)
    {
        $this->key = ConveyorGrinderKey::query()
            ->where('name', $key)
            ->first() ?? new ConveyorGrinderKey();

        if (!$this->key->exists) {
            $this->key->name = $key;
            $this->key->save();
        }
    }

    /**
     * Updated model values
     * main method
     * 
     * @param Model $model
     * @param string[] $only
     * @return void
     */
    final public function update(Model $model, array $only = []): void
    {
        if ($model::class !== $this->modelClass()) {
            throw new ConveyorException('Incompatible classes, expected ' . $this->modelClass() . ' got ' . $model::class);
        }

        $this->config = new GrinderSetup();
        $this->setup($this->config);


    }

    abstract protected function modelClass(): string;

    abstract function setup(GrinderSetup $setup): void;
}
