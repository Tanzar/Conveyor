<?php

namespace Tanzar\Conveyor\Tests\Unit\Cells;

use Tanzar\Conveyor\Cells\Cell;
use Tanzar\Conveyor\Exceptions\CellLockedException;
use Tanzar\Conveyor\Exceptions\CellValueException;
use Tanzar\Conveyor\Models\ConveyorCell;
use Tanzar\Conveyor\Models\ConveyorFrame;
use Tanzar\Conveyor\Tests\Models\Tester;
use Tanzar\Conveyor\Tests\TestCase;

class CellTest extends TestCase
{

    public function test_options(): void
    {
        $conveyorCell = new ConveyorCell();
        $conveyorCell->options = [];

        $cell = new Cell($conveyorCell);

        $cell->setOption('bool', true);
        $cell->setOption('float', 3.14);
        $cell->setOption('text', 'Hello World!');

        $this->assertTrue($cell->getOptionAsBool('bool'));
        $this->assertEquals(3.14, $cell->getOptionAsFloat('float'));
        $this->assertEquals('Hello World!', $cell->getOptionAsString('text'));
    }

    public function test_reactive_cell(): void
    {
        $val = 10;

        $cell = new Cell(new ConveyorCell());

        $cell->setReactive(function() use (&$val) {
            return $val + 20;
        });

        $this->assertEquals(30, $cell->getValue());
    }

    public function test_reactive_loop_breaking(): void
    {
        $first = new Cell(new ConveyorCell());
        $second = new Cell(new ConveyorCell());

        $first->setReactive(function () use ($second) {
            return 10 * $second->getValue();
        });

        $second->setReactive(function () use ($first) {
            return 5 * $first->getValue();
        });

        $this->expectException(CellLockedException::class);

        $first->getValue();
    }

    public function test_reactive_return_value_error(): void
    {
        $cell = new Cell(new ConveyorCell());

        $cell->setReactive(fn() => 'hello');
        
        $this->expectException(CellValueException::class);

        $cell->getValue();
    }

    public function test_saving_new_cell(): void
    {
        $conveyor = new ConveyorFrame();

        $conveyor->base_key = 'key';
        $conveyor->key = 'key_one';
        $conveyor->params = [];

        $conveyor->save();
        
        $conveyorCell = new ConveyorCell();
        $conveyorCell->conveyor_frame_id = $conveyor->id;
        $conveyorCell->key = 'row.col';
        $conveyorCell->hidden = false;
        $conveyorCell->value = 0;
        $conveyorCell->options = [];
        $conveyorCell->models = [];

        $tester = new Tester();
        $tester->save();

        Cell::$currentModel = $tester;

        $cell = new Cell($conveyorCell);
        $cell->change(10);
        $cell->setOption('day', true);
        $cell->save();

        $this->assertDatabaseHas(ConveyorCell::class, [
            'key' => 'row.col',
            'hidden' => false,
            'value' => 10,
            'options' => json_encode([ 'day' => true ]),
            'models' => json_encode([
                Tester::class => [ $tester->id => 10 ]
            ])
        ]);
    }
    
    public function test_updating_cell(): void
    {
        $conveyor = new ConveyorFrame();

        $conveyor->base_key = 'key';
        $conveyor->key = 'key_one';
        $conveyor->params = [];

        $conveyor->save();
        
        $first = new Tester();
        $first->save();

        $second = new Tester();
        $second->save();

        $conveyorCell = new ConveyorCell();
        $conveyorCell->conveyor_frame_id = $conveyor->id;
        $conveyorCell->key = 'row.col';
        $conveyorCell->hidden = true;
        $conveyorCell->value = 20;
        $conveyorCell->options = [ 'day' => false ];
        $conveyorCell->models = [
            Tester::class => [
                $first->id => 10,
                $second->id => 10,
            ]
        ];
        $conveyorCell->save();

        Cell::$currentModel = $first;

        $cell = new Cell($conveyorCell);
        $cell->change(5);
        $cell->setOption('day', true);

        Cell::$currentModel = $second;

        $cell->change(20);
        $cell->save();

        $this->assertDatabaseHas(ConveyorCell::class, [
            'key' => 'row.col',
            'hidden' => true,
            'value' => 25,
            'options' => json_encode([ 'day' => true ]),
            'models' => json_encode([
                Tester::class => [ $first->id => 5, $second->id => 20 ]
            ])
        ]);
    }
}
