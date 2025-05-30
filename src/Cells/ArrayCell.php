<?php

namespace Tanzar\Conveyor\Cells;

/**
 * Cell type used when cell should hold multiple values
 */
class ArrayCell extends DataCell
{
    private function __construct(private array $value) {}

    /**
     * gets current value of array field
     * @param string|int $key array index / key
     * @return float|string|null current value under given index, returns null if value is not set
     */
    public function get(string|int $key): float|string|null
    {
        return $this->value[$key] ?? null;
    }

    /**
     * sets $value at $key
     * @param string|int $key array index / key
     * @param float|string $value value set for $key
     * @return void
     */
    public function set(string|int $key, float|string $value): void
    {
        $this->value[$key] = $value;
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
