<?php

namespace Tanzar\Conveyor\Grinder\Cell;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Tanzar\Conveyor\Grinder\Params\GrinderParams;
use Tanzar\Conveyor\Models\ConveyorCalculableKey;
use Tanzar\Conveyor\Models\ConveyorGrinderKey;
use Tanzar\Conveyor\Models\ConveyorModelValue;

final class Cells
{
    private array $cells = [];
    private ConveyorCalculableKey $modelKey;

    public function __construct(
        private ConveyorGrinderKey $grinder,
        private Model $model,
        private GrinderParams $params
    ) {
        $this->initModelKey($model);
        $modelValues = $this->getModelValues();

        /** @var ConveyorModelValue $modelValue */
        foreach ($modelValues as $modelValue) {
            $cell = $modelValue->cell;
            $this->cells[$cell->cellKey->name] = new ValueCell(
                $cell->grinder,
                $cell->cellKey,
                $modelValue,
                $this->params->copy()
            );
        }
        
    }

    private function initModelKey(Model $model): void
    {
        $modelKey = ConveyorCalculableKey::query()
            ->where('model_class_name', $model::class)
            ->first();

        if (!$modelKey) {
            $modelKey = new ConveyorCalculableKey();
            $modelKey->model_class_name = $model::class;
            $modelKey->save();
        }

        $this->modelKey = $modelKey;
    }

    private function getModelValues(): Collection
    {
        return ConveyorModelValue::query()
            ->with([
                'cell',
                'cell.cellKey',
                'cell.params'
            ])
            ->whereHas('cell', fn(Builder $q) => $q->where('grinder_key_id', $this->grinder->id))
            ->where('calculable_key_id', $this->modelKey->id)
            ->where('calculable_id', $this->getPrimaryKey())
            ->get();
    }

    private function getPrimaryKey(): array
    {
        $name = $this->model->getKeyName();
        if (!is_array($name)) {
            return [
                $name => $this->model->$name
            ];
        }
        
        $result = [];
        foreach ($name as $key) {
            $result[$key] = $this->model->$key;
        }
        return $result;
    }
}
