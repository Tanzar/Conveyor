<?php

namespace Tanzar\Conveyor\Cells;

use Illuminate\Support\Collection;
use Tanzar\Conveyor\Models\ConveyorCell;
use Tanzar\Conveyor\Models\ConveyorFrame;

final class Cells implements CellsInterface
{

    private Collection $cells;

    public function __construct(private ConveyorFrame $frame)
    {
        $frame->loadMissing('cells');

        $this->cells = new Collection();

        foreach ($frame->cells as $frameCell) {
            $this->cells->put($frameCell->key, new Cell($frameCell));
        }
    }

    public function get(string...$keys): CellInterface
    {
        $key = $this->combineKeys($keys);

        if ($this->cells->doesntContain($key)) {
            $frameCell = new ConveyorCell();
            $frameCell->conveyor_frame_id = $this->frame->id;
            $frameCell->key = $key;

            $cell = new Cell($frameCell);
            $this->cells->put($key, $cell);
        } else {
            $cell = $this->cells->get($key);
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
}
