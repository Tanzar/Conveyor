<?php

namespace Tanzar\Conveyor\Base\Cells;

class TextCell extends DataCell
{

    public function __construct(
        private string $text
    ) {}

    /**
     * returns current cell value
     * @return float|string|array current cell value
     */
    public function getValue(): float|string|array
    {
        return $this->text;
    }

}
