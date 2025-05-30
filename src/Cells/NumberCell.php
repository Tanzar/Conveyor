<?php

namespace Tanzar\Conveyor\Cells;

/**
 * Simple cell for holding numeric values
 */
class NumberCell extends DataCell
{
    private float $value;

    /**
     * @param float $initial initial cell value
     */
    public function __construct(float $initial = 0)
    {
        $this->value = $initial;
    }

    /**
     * Changes cell value
     * @param float $value number added to current cell value, to reduce current value use negative values
     * @return void 
     */
    public function change(float $value = 1): void
    {
        $this->value += $value;
    }

    /**
     * returns current cell value
     * @return float|string|array current cell value
     */
    public function getValue(): float|string|array
    {
        return $this->value;
    }

}
