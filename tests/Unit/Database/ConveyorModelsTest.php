<?php

namespace Tanzar\Conveyor\Tests\Database\Unit;

use Tanzar\Conveyor\Models\ConveyorCell;
use Tanzar\Conveyor\Models\ConveyorFrame;
use Tanzar\Conveyor\Tests\Models\Tester;
use Tanzar\Conveyor\Tests\TestCase;

class ConveyorModelsTest extends TestCase
{
    
    public function test_conveyor_tables(): void
    {
        $conveyor = new ConveyorFrame();

        $conveyor->base_key = 'key';
        $conveyor->key = 'key_one';
        $conveyor->params = [];

        $conveyor->save();

        $cell = new ConveyorCell();
        $cell->key = 'row.col';
        $cell->hidden = false;
        $cell->value = 10;
        $cell->options = [ 'marked' => false ];
        $cell->models = [
            Tester::class => [
                1 => 2,
                2 => 13,
            ]
        ];

        $conveyor->cells()->save($cell);


        $this->assertDatabaseHas(ConveyorFrame::class, [
            'base_key' => 'key',
            'key' => 'key_one'
        ]);

        $this->assertDatabaseHas(ConveyorCell::class, [
            'key' => 'row.col',
            'hidden' => false,
            'value' => 10,
            'options' => json_encode([ 'marked' => false ]),
            'models' => json_encode([ Tester::class => [ 1 => 2, 2 => 13 ] ])
        ]);

    }

}
