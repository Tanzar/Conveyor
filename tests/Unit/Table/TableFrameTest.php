<?php

namespace Tanzar\Conveyor\Tests\Unit\Table;

use Tanzar\Conveyor\Table\Frame\Column;
use Tanzar\Conveyor\Table\Frame\Columns;
use Tanzar\Conveyor\Table\Frame\Row;
use Tanzar\Conveyor\Table\Frame\Rows;
use Tanzar\Conveyor\Tests\TestCase;

class TableFrameTest extends TestCase
{

    public function test_rows_correct_order(): void
    {
        $rows = new Rows();

        $rows->add('one', 'one');
        $rows->add('hidden', 'hidden')->hide();
        $rows->after('one', 'after', 'after');
        $rows->before('one', 'before', 'before');

        $expected = [
            [ 'key' => 'before', 'label' => 'before', 'options' => [] ],
            [ 'key' => 'one', 'label' => 'one', 'options' => [] ],
            [ 'key' => 'after', 'label' => 'after', 'options' => [] ],
        ];

        $this->assertEquals($expected, $rows->toArray());
    }

    public function test_rows_each_method(): void
    {
        $rows = new Rows();

        $rows->add('one', 'one');
        $rows->add('hidden', 'hidden')->hide();
        $rows->after('one', 'after', 'after');
        $rows->before('one', 'before', 'before');

        $keys = [];

        $rows->each(function (Row $row) use (&$keys) {
            $keys[] = $row->key;
        });

        $expected = ['before', 'one', 'after'];

        $this->assertEquals($expected, $keys);
    }

    public function test_columns_correct_order(): void
    {
        $columns = new Columns();

        $columns->add('one', 'one');
        $columns->add('hidden', 'hidden')->hide();
        $columns->after('one', 'after', 'after');
        $columns->before('one', 'before', 'before');

        $expected = [
            [ 'key' => 'before', 'label' => 'before', 'options' => [] ],
            [ 'key' => 'one', 'label' => 'one', 'options' => [] ],
            [ 'key' => 'after', 'label' => 'after', 'options' => [] ],
        ];

        $this->assertEquals($expected, $columns->toArray());
    }

    public function test_columns_each_method(): void
    {
        $columns = new Columns();

        $columns->add('one', 'one');
        $columns->add('hidden', 'hidden')->hide();
        $columns->after('one', 'after', 'after');
        $columns->before('one', 'before', 'before');

        $keys = [];

        $columns->each(function (Column $column) use (&$keys) {
            $keys[] = $column->key;
        });

        $expected = ['before', 'one', 'after'];

        $this->assertEquals($expected, $keys);
    }
}
