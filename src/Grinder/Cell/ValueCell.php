<?php

namespace Tanzar\Conveyor\Grinder\Cell;

use Illuminate\Database\Eloquent\Builder;
use Tanzar\Conveyor\Grinder\Params\GrinderParams;
use Tanzar\Conveyor\Grinder\Params\Params;
use Tanzar\Conveyor\Models\ConveyorCellKey;
use Tanzar\Conveyor\Models\ConveyorCellValue;
use Tanzar\Conveyor\Models\ConveyorGrinderKey;
use Tanzar\Conveyor\Models\ConveyorModelValue;

final class ValueCell implements Cell
{
    private float $newValue = 0.0;
    

    public function __construct(
        private ConveyorGrinderKey $grinderKey,
        private ConveyorCellKey $cellKey,
        private ConveyorModelValue $modelValue,
        private GrinderParams $newParams
    ) {
        
    }

    public function setValue(float $value): Cell
    {
        $this->newValue = $value;
        return $this;
    }

    public function params(): Params
    {
        return $this->newParams;
    }

    public function save(): void
    {
        if ($this->modelValue->exists) {
            $this->updateModelValue();
        } else {
            $this->saveNewModelValue();
        }
    }

    private function updateModelValue(): void
    {
        $cell = $this->getCellValue();

        if ($this->isCellMatching($cell)) {
            $cell->cell_value -= $this->modelValue->cell_value;
        } else {
            $this->modelValue->cell->cell_value -= $this->modelValue->cell_value;
            if ($this->modelValue->cell->cell_value === 0.0) {
                $this->modelValue->cell->delete();
            } else {
                $this->modelValue->cell->save();
            }
            
            $this->modelValue->cell_id = $cell->id;
        }
      
        if ($this->newValue !== 0.0) {
            $cell->cell_value += $this->newValue;   
            $this->modelValue->cell_value = $this->newValue;
            $this->modelValue->save();
        } else {
            $this->modelValue->delete();
        }

        if ($cell->cell_value === 0.0) {
            $cell->delete();
        } else {
            $cell->save();
        }
    }

    private function isCellMatching(ConveyorCellValue $cell): bool
    {
        return $cell->id === $this->modelValue->cell->id;
    }



    private function saveNewModelValue(): void
    {
        if ($this->newValue === 0.0) {
            return;
        }

        $cell = $this->getCellValue();

        $cell->cell_value += $this->newValue;
        $cell->save();

        $this->modelValue->cell_id = $cell->id;
        $this->modelValue->cell_value = $this->newValue;
        $this->modelValue->save();
    }
    
    private function getCellValue(): ConveyorCellValue
    {
        $query = ConveyorCellValue::query()
            ->where('grinder_key_id', $this->grinderKey->id)
            ->where('cell_key_id', $this->cellKey->id);

        foreach ($this->newParams->getModels() as $param) {
            $query->whereHas('params', fn (Builder $query) =>
                $query->where('name', $param->name)
                    ->where('param_value', $param->param_value)
            );
        }

        $cell = $query->first();

        if (!$cell) {
            $cell = $this->saveNewCell();
        }

        return $cell;
    }
    
    private function saveNewCell(): ConveyorCellValue
    {
        $cell = new ConveyorCellValue();
        $cell->cell_key_id = $this->cellKey->id;
        $cell->grinder_key_id = $this->grinderKey->id;
        $cell->cell_value = 0;
        $cell->save();

        foreach ($this->newParams->getModels() as $paramModel) {
            $cell->params()->attach($paramModel);
        }

        return $cell;
    }

}
