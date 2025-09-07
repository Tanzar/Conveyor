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
use Tanzar\Conveyor\Events\ConveyorUpdated;
use Tanzar\Conveyor\Exceptions\InvalidCallException;
use Tanzar\Conveyor\Exceptions\InvalidClassException;
use Tanzar\Conveyor\Models\ConveyorFrame;
use Tanzar\Conveyor\Params\Params;
use Tanzar\Conveyor\Params\ParamsInitializer;

abstract class ConveyorCore
{
    private ConveyorConfig $config;
    private ?Cells $cells = null;
    private Params $params;

    protected int $chunk = 1000;

    public function __construct(private ?string $baseKey)
    {
        $this->config = new ConveyorConfig();
        $this->setup($this->config);
    }

    abstract protected function setup(ConveyorConfigInterface $config): void;

    final public function getInitializer(bool $withOptions): ParamsInitializer
    {
        $initializer = new ParamsInitializer(
            $this->baseKey,
            $this->rules(),
            $this->ingoreForKey()
        );

        if ($withOptions) {
            $this->initializationOptions($initializer);
        }

        return $initializer;
    }

    /**
     * Parameters validation rules
     * add all parameters you intend to use in conveyor
     * @return array
     */
    protected function rules(): array
    {
        return [];
    }

    /**
     * array of keys ignored for conveyor key
     * this values will not be used for keys in database
     * @return string[]
     */
    protected function ingoreForKey(): array
    {
        return [];
    }

    protected function initializationOptions(ParamsInitializer $initializer): void {}

    final public function update(ConveyorFrame $frame, ?Model $model = null): void
    {
        $this->params = new Params($frame->params);
        $this->cells = new Cells($frame);

        $this->runSetup();

        if ($model) {
            $this->updateModel($model);
        } else {
            $this->updateAll();
        }

        $this->postProcessing();

        $this->cells->save();

        ConveyorUpdated::dispatch($frame);
    }

    /**
     * use to run configs for your conveyor
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
            Cell::$currentModel = $model;
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

    public function formatData(ConveyorFrame $frame): array
    {
        $this->params = new Params($frame->params);

        $this->cells = new Cells($frame);

        $this->runSetup();

        $this->postProcessing();

        return $this->format();
    }

    abstract protected function format(): array;

    final public function getCellModelsIds(ConveyorFrame $frame, string $modelClass, array $cellKeys): array
    {
        $config = $this->config->toArray();
        if (!isset($config[$modelClass])) {
            throw new InvalidClassException("Model $modelClass is not set in conveyor");
        }

        $this->params = new Params($frame->params);

        $this->cells = new Cells($frame);

        $this->runSetup();

        $this->postProcessing();

        $models = $this->cells->getCellModels($cellKeys);

        if (!isset($models[$modelClass])) {
            return [];
        }

        return collect($models[$modelClass])->keys()->toArray();
    }

    private function initProperties(ConveyorFrame $frame): void
    {
        
    }

    final protected function cells(): CellsInterface
    {
        if ($this->cells === null) {
            throw new InvalidCallException('Method cells called incorectly, call only within ConveyorCore handlers');
        }
        return $this->cells;
    }
}
