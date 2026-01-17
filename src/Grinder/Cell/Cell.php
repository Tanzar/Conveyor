<?php

namespace Tanzar\Conveyor\Grinder\Cell;

use Tanzar\Conveyor\Grinder\Params\Params;

interface Cell
{

    public function setValue(float $value): Cell;

    public function params(): Params;

}
