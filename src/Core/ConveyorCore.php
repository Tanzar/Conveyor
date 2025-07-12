<?php

namespace Tanzar\Conveyor\Core;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Tanzar\Conveyor\Cells\Cell;
use Tanzar\Conveyor\Cells\Cells;
use Tanzar\Conveyor\Cells\CellsInterface;
use Tanzar\Conveyor\Configs\ConveyorConfig;
use Tanzar\Conveyor\Configs\ConveyorConfigInterface;
use Tanzar\Conveyor\Configs\ModelConfig;
use Tanzar\Conveyor\Exceptions\InvalidCallException;
use Tanzar\Conveyor\Models\ConveyorFrame;
use Tanzar\Conveyor\Params\Params;
use Tanzar\Conveyor\Params\ParamsInitializer;

abstract class ConveyorCore
{
    private ConveyorConfig $config;
    private ?Cells $cells = null;
    private Params $params;
    private ParamsInitializer $paramsInitializer;

    protected int $chunk = 1000;

    public function __construct(private ?string $baseKey)
    {
        $this->config = new ConveyorConfig();
        $this->setup($this->config);
        $this->paramsInitializer = $this->initializer();
    }

    abstract protected function setup(ConveyorConfigInterface $config): void;

    /**
     * Setup for conveyor initializer, used by commands to setup
     * @return ParamsInitializer
     */
    protected function initializer(): ParamsInitializer
    {
        return new ParamsInitializer();
    }

    final public function getInitializer(): ParamsInitializer
    {
        return $this->paramsInitializer;
    }

    final public function run(ConveyorFrame $frame, ?Model $model = null, bool $update = true): void
    {
        $this->params = new Params($frame->params);
        $this->cells = new Cells($frame);

        $this->runSetup();

        if ($update) {
            if ($model) {
                $this->updateModel($model);
            } else {
                $this->updateAll();
            }
        }

        $this->postProcessing();

        $this->cells->save();
    }

    /**
     * Runs every time Conveyor calculates but before calculations
     * @return void
     */
    protected function runSetup(): void { }

    private function updateModel(Model $model): void
    {
        $config = $this->config->get($model::class);
        if ($config === null) {
            return;
        }

        $column = $model->getKeyName();
        $id = $model->getKey();

        $queryRetrievesModel = $config->getQuery($this->params)
            ->where(fn(Builder $query) => $query->where($column, $id))
            ->exists();

        if ($queryRetrievesModel) {
            $model->loadMissing($config->getRelations());

            $this->handle($model, $config);
        } else {
            $this->cells->removeModel($model);
        }
    }

    private function updateAll(): void
    {
        foreach ($this->config->toArray() as $modelConfig) {
            
            $modelConfig->getQuery($this->params, true)
                ->lazyById($this->chunk, $modelConfig->getIdColumn())
                ->each(function(Model $model) use ($modelConfig) {
                    $this->handle($model, $modelConfig);
                });
        }
    }

    private function handle(Model $model, ModelConfig $config): void
    {
        Cell::$currentModel = $model;
        foreach ($config->getHandlers() as $handler) {
            $handler($model);
            $this->cells->removeModel($model);
        }
        Cell::$currentModel = null;
    }

    /**
     * Runs after calculations but before cells are saved
     * @return void
     */
    protected function postProcessing(): void { }

    final public function allowAccess(ConveyorFrame $frame): bool
    {
        return $this->allow(new Params($frame->params));
    }

    /**
     * Check if user is allowed to access conveyor broadcast
     * @param Params $params
     * @return bool
     */
    protected function allow(Params $params): bool
    {
        return true;
    }

    abstract public function format(): array;

    final protected function cells(): CellsInterface
    {
        if ($this->cells === null) {
            throw new InvalidCallException('Method cells called incorectly, call only within ConveyorCore handlers');
        }
        return $this->cells;
    }
}
