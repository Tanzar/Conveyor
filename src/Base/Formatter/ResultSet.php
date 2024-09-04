<?php

namespace Tanzar\Conveyor\Base\Formatter;

use Tanzar\Conveyor\Base\Cells\DataCell;

abstract class ResultSet
{

    abstract public function get(string... $keys): ?DataCell;

    
}
