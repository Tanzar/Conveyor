<?php

namespace Tanzar\Conveyor\Cells;

interface CellsInterface
{
    public function get(string...$keys): CellInterface;
}
