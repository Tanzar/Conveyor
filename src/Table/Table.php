<?php

namespace Tanzar\Conveyor\Table;

use Tanzar\Conveyor\Cells\CellInterface;
use Tanzar\Conveyor\Core\ConveyorCore;
use Tanzar\Conveyor\Table\Frame\Column;
use Tanzar\Conveyor\Table\Frame\Columns;
use Tanzar\Conveyor\Table\Frame\Row;
use Tanzar\Conveyor\Table\Frame\Rows;

abstract class Table extends ConveyorCore
{
    private Rows $rows;
    private Columns $columns;

    final protected function runSetup(): void
    {
        $this->rows = new Rows();
        $this->columns = new Columns();
        $this->setupRows($this->rows);
        $this->setupColumns($this->columns);
    }

    abstract function setupRows(Rows $rows): void;

    abstract function setupColumns(Columns $columns): void;

    public function format(): array
    {
        return [
            'rows' => $this->rows->toArray(),
            'columns' => $this->columns->toArray(),
            'cells' => $this->formatCells()
        ];
    }

    private function formatCells(): array
    {
        $cells = [];

        $this->rows->each(function(Row $row) use (&$cells) {
            $newRow = [];
            $this->columns->each(function (Column $column) use (&$newRow, $row) {
                $newRow[$column->key] = $this->get($row, $column)->getValue();
            });
            $cells[$row->key] = $newRow;
        });

        return $cells;
    }

    /**
     * Get cell under given row and column
     * @param string|Row $row
     * @param string|Column $column
     * @return CellInterface|null
     */
    public function get(string|Row $row, string|Column $column): ?CellInterface
    {
        $rowKey = ($row instanceof Row) ? $row->key : $row;
        $colKey = ($column instanceof Column) ? $column->key : $column;
        if ($this->rows->isSet($rowKey) && $this->columns->isSet($colKey)) {
            return $this->cells()->get($rowKey, $colKey);
        }
        return null;
    }

    final protected function rows(): Rows
    {
        return $this->rows;
    }

    final protected function columns(): Columns
    {
        return $this->columns;
    }
}
