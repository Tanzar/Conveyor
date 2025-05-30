<?php

namespace Tanzar\Conveyor\Cells;

/**
 * Basic class for containing values
 */
abstract class DataCell
{
    private bool $hidden = false;

    /**
     * sets if cell will be shown in resulting json (true / false), and 
     * @param ?bool $hide sets if cell should be shown in formatters, null skips this options
     * @return bool current cell state
     */
    public function hidden(?bool $hide = null): bool
    {
        if ($hide !== null) {
            $this->hidden = $hide;
        }
        return $this->hidden;
    }

    abstract public function getValue(): float|string|array;
}
