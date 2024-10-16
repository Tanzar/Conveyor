<?php

namespace Tanzar\Conveyor\Base\Cells;

use Illuminate\Support\Collection;

class DataCells
{

    private Collection $cells;

    public function __construct()
    {
        $this->cells = new Collection();
    }


    public function set(DataCell $cell, string...$keys): void
    {
        $this->cells->put($this->combineKeys($keys), $cell);
    }

    public function get(string...$keys): ?DataCell
    {
        return $this->cells->get($this->combineKeys($keys));
    }

    private function combineKeys(array $keys): string
    {
        $cellKey = '';
        foreach ($keys as $key) {
            $cellKey .= "$key.";
        }
        return $cellKey;
    }

    public function reset(): void
    {
        $this->cells = new Collection();
    }
}
