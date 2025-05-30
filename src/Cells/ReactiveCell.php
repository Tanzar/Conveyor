<?php

namespace Tanzar\Conveyor\Cells;

use Closure;
use Tanzar\Conveyor\Exceptions\CellLockedException;
use Tanzar\Conveyor\Exceptions\CellValueException;

/**
 * Cell allows to automatically calculate resulting value
 * useful if cell must do calculations before its value is shown
 */
final class ReactiveCell extends DataCell
{
    private bool $locked = false;

    public function __construct(
        private Closure $recalculate
    ) {

    }

    /**
     * gets current cell value
     * @throws CellLockedException thrown to prevent cells from depending on eachother
     * @throws CellValueException thrown if closure returns incorrect data type
     * @return float|string|array current cell value
     */
    public function getValue(): float|string|array
    {
        if ($this->locked) {
            throw new CellLockedException();
        }
        $this->locked = true;
        $recalculate = $this->recalculate;
        $value = $recalculate();
        if (
            !is_int($value) &&
            !is_float($value) &&
            !is_array($value) &&
            !is_array($value)
        ) {
            throw new CellValueException('Incorrect cell value type returned, only int, float, string and array allowed.');
        }
        $this->locked = false;
        return $value;
    }

}
