<?php

namespace Tanzar\Conveyor\Grinder\Cell;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Tanzar\Conveyor\Models\ConveyorGrinderKey;
use Tanzar\Conveyor\Models\ConveyorModelValue;

final class Cells
{
    private array $cells = [];

    public function __construct(private ConveyorGrinderKey $grinder, private Model $model)
    {
        $modelValues = $this->getModelValues();

        
        
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
            ->whereHas('calculableKey', fn(Builder $q) => $q->where('name', $this->model::class))
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
