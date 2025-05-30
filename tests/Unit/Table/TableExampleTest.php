<?php

namespace Tanzar\Conveyor\Tests\Unit\Table;

use Tanzar\Conveyor\Tests\Classes\TableExample;
use Tanzar\Conveyor\Tests\TestCase;

class TableExampleTest extends TestCase
{

    public function test_table_example_format(): void
    {
        $table = new TableExample();

        $json = $table->run();

        $expected = [
            'rows' => [
                [ 'key' => 'total', 'label' => 'Total', 'options' => [] ],
                [ 'key' => 'pizzas', 'label' => 'Pizzas', 'options' => [] ],
                [ 'key' => 'burgers', 'label' => 'Burgers', 'options' => [] ],
            ],
            'columns' => [
                [ 'key' => 'today', 'label' => 'First', 'options' => [] ],
                [ 'key' => 'yesterday', 'label' => 'Second', 'options' => [] ],
            ],
            'cells' => [
                'total' => [
                    'today' => 8.0,
                    'yesterday' => 3.0,
                ],
                'pizzas' => [
                    'today' => 3.0,
                    'yesterday' => 2.0,
                ],
                'burgers' => [
                    'today' => 5.0,
                    'yesterday' => 1.0,
                ],
            ],
        ];

        $this->assertEquals($expected, $json);
    }
}
