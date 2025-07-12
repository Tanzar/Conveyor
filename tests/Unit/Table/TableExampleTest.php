<?php

namespace Tanzar\Conveyor\Tests\Unit\Table;

use Tanzar\Conveyor\Models\ConveyorCell;
use Tanzar\Conveyor\Models\ConveyorFrame;
use Tanzar\Conveyor\Tests\Classes\TableExample;
use Tanzar\Conveyor\Tests\Models\Food;
use Tanzar\Conveyor\Tests\TestCase;

class TableExampleTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Food::factory()->count(5)->create([
            'type' => 'pizza',
            'day' => 'today'
        ]);

        Food::factory()->count(10)->create([
            'type' => 'pizza',
            'day' => 'yesterday'
        ]);

        Food::factory()->count(2)->create([
            'type' => 'burger',
            'day' => 'today'
        ]);

        Food::factory()->count(7)->create([
            'type' => 'burger',
            'day' => 'yesterday'
        ]);
    }

    public function test_table_example_initializing(): void
    {
        $frame = new ConveyorFrame();
        $frame->key = 'table-';
        $frame->base_key = 'table';
        $frame->params = [];
        $frame->save();

        $table = new TableExample('table');

        $table->run($frame);

        $expected = [
            'rows' => [
                [ 'key' => 'burger', 'label' => 'Burgers', 'options' => [] ],
                [ 'key' => 'pizza', 'label' => 'Pizzas', 'options' => [] ],
            ],
            'columns' => [
                [ 'key' => 'total', 'label' => 'Sum', 'options' => [] ],
                [ 'key' => 'today', 'label' => 'Today', 'options' => [] ],
                [ 'key' => 'yesterday', 'label' => 'Yesterday', 'options' => [] ],
            ],
            'cells' => [
                'pizza' => [
                    'today' => 5.0,
                    'yesterday' => 10.0,
                    'total' => 15.0,
                ],
                'burger' => [
                    'today' => 2.0,
                    'yesterday' => 7.0,
                    'total' => 9.0,
                ],
            ],
        ];

        $this->assertEquals($expected, $table->format());

        $this->assertDatabaseHas(ConveyorFrame::class, [
            'key' => 'table-',
            'base_key' => 'table',
            'params' => '[]'
        ]);

        $this->assertDatabaseHas(ConveyorCell::class, [
            'key' => 'pizza.today.',
            'value' => 5.0,
            'models' => json_encode([
                Food::class => [ 1 => 1, 2 => 1, 3 => 1, 4 => 1, 5 => 1 ]
            ]),
        ]);

        $this->assertDatabaseHas(ConveyorCell::class, [
            'key' => 'pizza.yesterday.',
            'value' => 10.0,
            'models' => json_encode([
                Food::class => [ 6 => 1, 7 => 1, 8 => 1, 9 => 1, 10 => 1, 11 => 1, 12 => 1, 13 => 1, 14 => 1, 15 => 1 ]
            ]),
        ]);

        $this->assertDatabaseHas(ConveyorCell::class, [
            'key' => 'burger.today.',
            'value' => 2.0,
            'models' => json_encode([
                Food::class => [ 16 => 1, 17 => 1 ]
            ]),
        ]);

        $this->assertDatabaseHas(ConveyorCell::class, [
            'key' => 'burger.yesterday.',
            'value' => 7.0,
            'models' => json_encode([
                Food::class => [ 18 => 1, 19 => 1, 20 => 1, 21 => 1, 22 => 1, 23 => 1, 24 => 1 ]
            ]),
        ]);

        $this->assertDatabaseMissing(ConveyorCell::class, [
            'key' => 'pizza.total.'
        ]);
        
        $this->assertDatabaseMissing(ConveyorCell::class, [
            'key' => 'burger.total.'
        ]);
    }

    public function test_update_model(): void
    {
        $frame = new ConveyorFrame();
        $frame->key = 'table-';
        $frame->base_key = 'table';
        $frame->params = [];
        $frame->save();

        $first = new ConveyorCell();
        $first->key = 'pizza.today.';
        $first->value = 5.0;
        $first->models = [ Food::class => [ 1 => 1, 2 => 1, 3 => 1, 4 => 1, 5 => 1 ] ];
        $frame->cells()->save($first);
        
        $second = new ConveyorCell();
        $second->key = 'pizza.yesterday.';
        $second->value = 10.0;
        $second->models = [
                Food::class => [ 6 => 1, 7 => 1, 8 => 1, 9 => 1, 10 => 1, 11 => 1, 12 => 1, 13 => 1, 14 => 1, 15 => 1 ]
        ];
        $frame->cells()->save($second);

        $third = new ConveyorCell();
        $third->key = 'burger.today.';
        $third->value = 2.0;
        $third->models = [
                Food::class => [ 16 => 1, 17 => 1 ]
        ];
        $frame->cells()->save($third);

        $fourth = new ConveyorCell();
        $fourth->key = 'burger.yesterday.';
        $fourth->value = 7.0;
        $fourth->models = [
                Food::class => [ 18 => 1, 19 => 1, 20 => 1, 21 => 1, 22 => 1, 23 => 1, 24 => 1 ]
        ];
        $frame->cells()->save($fourth);

        /** @var Food $model */
        $model = Food::find(19);
        $model->type = 'pizza';
        $model->day = 'today';
        $model->save();

        $table = new TableExample('table');

        $table->run($frame, $model);

        $expected = [
            'rows' => [
                [ 'key' => 'burger', 'label' => 'Burgers', 'options' => [] ],
                [ 'key' => 'pizza', 'label' => 'Pizzas', 'options' => [] ],
            ],
            'columns' => [
                [ 'key' => 'total', 'label' => 'Sum', 'options' => [] ],
                [ 'key' => 'today', 'label' => 'Today', 'options' => [] ],
                [ 'key' => 'yesterday', 'label' => 'Yesterday', 'options' => [] ],
            ],
            'cells' => [
                'pizza' => [
                    'today' => 6.0,
                    'yesterday' => 10.0,
                    'total' => 16.0,
                ],
                'burger' => [
                    'today' => 2.0,
                    'yesterday' => 6.0,
                    'total' => 8.0,
                ],
            ],
        ];

        $this->assertEquals($expected, $table->format());

        $this->assertDatabaseHas(ConveyorFrame::class, [
            'key' => 'table-',
            'base_key' => 'table',
            'params' => '[]'
        ]);

        $this->assertDatabaseHas(ConveyorCell::class, [
            'key' => 'pizza.today.',
            'value' => 6.0,
            'models' => json_encode([
                Food::class => [ 1 => 1, 2 => 1, 3 => 1, 4 => 1, 5 => 1, 19 => 1 ]
            ]),
        ]);

        $this->assertDatabaseHas(ConveyorCell::class, [
            'key' => 'pizza.yesterday.',
            'value' => 10.0,
            'models' => json_encode([
                Food::class => [ 6 => 1, 7 => 1, 8 => 1, 9 => 1, 10 => 1, 11 => 1, 12 => 1, 13 => 1, 14 => 1, 15 => 1 ]
            ]),
        ]);

        $this->assertDatabaseHas(ConveyorCell::class, [
            'key' => 'burger.today.',
            'value' => 2.0,
            'models' => json_encode([
                Food::class => [ 16 => 1, 17 => 1 ]
            ]),
        ]);

        $this->assertDatabaseHas(ConveyorCell::class, [
            'key' => 'burger.yesterday.',
            'value' => 6.0,
            'models' => json_encode([
                Food::class => [ 18 => 1, 20 => 1, 21 => 1, 22 => 1, 23 => 1, 24 => 1 ]
            ]),
        ]);

        $this->assertDatabaseMissing(ConveyorCell::class, [
            'key' => 'pizza.total.'
        ]);
        
        $this->assertDatabaseMissing(ConveyorCell::class, [
            'key' => 'burger.total.'
        ]);
    }

    public function test_update_disabled(): void
    {
        $frame = new ConveyorFrame();
        $frame->key = 'table-';
        $frame->base_key = 'table';
        $frame->params = [];
        $frame->save();

        $first = new ConveyorCell();
        $first->key = 'pizza.today.';
        $first->value = 5.0;
        $first->models = [ Food::class => [ 1 => 1, 2 => 1, 3 => 1, 4 => 1, 5 => 1 ] ];
        $frame->cells()->save($first);
        
        $second = new ConveyorCell();
        $second->key = 'pizza.yesterday.';
        $second->value = 10.0;
        $second->models = [
                Food::class => [ 6 => 1, 7 => 1, 8 => 1, 9 => 1, 10 => 1, 11 => 1, 12 => 1, 13 => 1, 14 => 1, 15 => 1 ]
        ];
        $frame->cells()->save($second);

        $third = new ConveyorCell();
        $third->key = 'burger.today.';
        $third->value = 2.0;
        $third->models = [
                Food::class => [ 16 => 1, 17 => 1 ]
        ];
        $frame->cells()->save($third);

        $fourth = new ConveyorCell();
        $fourth->key = 'burger.yesterday.';
        $fourth->value = 7.0;
        $fourth->models = [
                Food::class => [ 18 => 1, 19 => 1, 20 => 1, 21 => 1, 22 => 1, 23 => 1, 24 => 1 ]
        ];
        $frame->cells()->save($fourth);

        /** @var Food $model */
        $model = Food::find(19);
        $model->type = 'pizza';
        $model->day = 'today';
        $model->save();

        $table = new TableExample('table');

        $table->run($frame, $model, false);

        $expected = [
            'rows' => [
                [ 'key' => 'burger', 'label' => 'Burgers', 'options' => [] ],
                [ 'key' => 'pizza', 'label' => 'Pizzas', 'options' => [] ],
            ],
            'columns' => [
                [ 'key' => 'total', 'label' => 'Sum', 'options' => [] ],
                [ 'key' => 'today', 'label' => 'Today', 'options' => [] ],
                [ 'key' => 'yesterday', 'label' => 'Yesterday', 'options' => [] ],
            ],
            'cells' => [
                'pizza' => [
                    'today' => 5.0,
                    'yesterday' => 10.0,
                    'total' => 15.0,
                ],
                'burger' => [
                    'today' => 2.0,
                    'yesterday' => 7.0,
                    'total' => 9.0,
                ],
            ],
        ];

        $this->assertEquals($expected, $table->format());

        $this->assertDatabaseHas(ConveyorFrame::class, [
            'key' => 'table-',
            'base_key' => 'table',
            'params' => '[]'
        ]);

        $this->assertDatabaseHas(ConveyorCell::class, [
            'key' => 'pizza.today.',
            'value' => 5.0,
            'models' => json_encode([
                Food::class => [ 1 => 1, 2 => 1, 3 => 1, 4 => 1, 5 => 1 ]
            ]),
        ]);

        $this->assertDatabaseHas(ConveyorCell::class, [
            'key' => 'pizza.yesterday.',
            'value' => 10.0,
            'models' => json_encode([
                Food::class => [ 6 => 1, 7 => 1, 8 => 1, 9 => 1, 10 => 1, 11 => 1, 12 => 1, 13 => 1, 14 => 1, 15 => 1 ]
            ]),
        ]);

        $this->assertDatabaseHas(ConveyorCell::class, [
            'key' => 'burger.today.',
            'value' => 2.0,
            'models' => json_encode([
                Food::class => [ 16 => 1, 17 => 1 ]
            ]),
        ]);

        $this->assertDatabaseHas(ConveyorCell::class, [
            'key' => 'burger.yesterday.',
            'value' => 7.0,
            'models' => json_encode([
                Food::class => [ 18 => 1, 19 => 1, 20 => 1, 21 => 1, 22 => 1, 23 => 1, 24 => 1 ]
            ]),
        ]);

        $this->assertDatabaseMissing(ConveyorCell::class, [
            'key' => 'pizza.total.'
        ]);
        
        $this->assertDatabaseMissing(ConveyorCell::class, [
            'key' => 'burger.total.'
        ]);
    }
}
