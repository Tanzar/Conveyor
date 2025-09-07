<?php

namespace Tanzar\Conveyor\Cells;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Tanzar\Conveyor\Exceptions\CellNotExistException;
use Tanzar\Conveyor\Models\ConveyorCell;
use Tanzar\Conveyor\Models\ConveyorFrame;

final class Cells implements CellsInterface
{

    private Collection $cells;

    public function __construct(private ConveyorFrame $frame)
    {
        $frame->load('cells');

        $this->cells = new Collection();

        foreach ($frame->cells as $frameCell) {
            $this->cells->put($frameCell->key, new Cell($frameCell));
        }
    }

    public function getCellModels(array $cellKeys): array
    {
        $key = $this->combineKeys($cellKeys);

        $cell = $this->cells->get($key);

        if ($cell === null) {
            throw new CellNotExistException('Cell not exist for keys: ' . implode(', ', $cellKeys));
        }

        /** @var Cell $cell */

        return $cell->getModels();
    }

    public function get(string...$keys): CellInterface
    {
        $key = $this->combineKeys($keys);

        $cell = $this->cells->get($key);

        if ($cell === null) {
            $frameCell = new ConveyorCell();
            $frameCell->conveyor_frame_id = $this->frame->id;
            $frameCell->key = $key;
            $frameCell->value = 0;
            $frameCell->models = [];

            $cell = new Cell($frameCell);
            $this->cells->put($key, $cell);
        }

        return $cell;
    }

    private function combineKeys(array $keys): string
    {
        $cellKey = '';
        foreach ($keys as $key) {
            $cellKey .= "$key.";
        }
        return $cellKey;
    }

    public function save(): void
    {
        foreach ($this->cells as $cell) {
            $cell->save();
        }
    }

    public function removeModel(Model $model): void
    {
        foreach ($this->cells as $cell) {
            $cell->removeModel($model);
        }
    }
}
