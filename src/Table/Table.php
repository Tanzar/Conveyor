<?php

namespace Tanzar\Conveyor\Table;

use Tanzar\Conveyor\AbstractConveyor;
use Tanzar\Conveyor\Cells\DataCell;
use Tanzar\Conveyor\Table\Frame\Column;
use Tanzar\Conveyor\Table\Frame\Columns;
use Tanzar\Conveyor\Table\Frame\Row;
use Tanzar\Conveyor\Table\Frame\Rows;

abstract class Table extends AbstractConveyor
{
    private Rows $rows;
    private Columns $columns;

    public function __construct()
    {
        parent::__construct();
        $this->rows = new Rows();
        $this->columns = new Columns();
    }

    protected function setup(): void
    {
        $this->setupRows($this->rows);
        $this->setupColumns($this->columns);
    }

    abstract function setupRows(Rows $rows): void;

    abstract function setupColumns(Columns $columns): void;

    protected function format(): array
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

    public function get(string|Row $row, string|Column $column): ?DataCell
    {
        $rowKey = ($row instanceof Row) ? $row->key : $row;
        $colKey = ($column instanceof Column) ? $column->key : $column;
        if ($this->rows->isSet($rowKey) && $this->columns->isSet($colKey)) {
            $cell = $this->cells()->get($rowKey, $colKey);
            if ($cell === null) {
                $cell = $this->defaultCell($rowKey, $colKey);
                $this->cells()->set($cell, $rowKey, $colKey);
            }
            return $cell;
        }
        return null;
    }
    
    abstract protected function defaultCell(string $row, string $column): DataCell;

}
