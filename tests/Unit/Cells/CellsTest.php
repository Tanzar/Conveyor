<?php

namespace Tanzar\Conveyor\Tests\Unit\Cells;

use Tanzar\Conveyor\Cells\Cell;
use Tanzar\Conveyor\Cells\Cells;
use Tanzar\Conveyor\Models\ConveyorCell;
use Tanzar\Conveyor\Models\ConveyorFrame;
use Tanzar\Conveyor\Tests\Models\Tester;
use Tanzar\Conveyor\Tests\TestCase;

class CellsTest extends TestCase
{

    public function test_saving_new(): void
    {
        $frame = new ConveyorFrame();
        $frame->base_key = 'key';
        $frame->key = 'key';
        $frame->params = [];
        $frame->save();

        $tester = new Tester();
        $tester->save();

        Cell::$currentModel = $tester;

        $cells = new Cells($frame);

        $cells->get('one', 'one')->change(10);
        $cells->get('one', 'two')->change(5);

        $cells->save();

        $this->assertDatabaseHas(ConveyorCell::class, [
            'key' => 'one.one.',
            'value' => 10,
            'models' => json_encode([
                Tester::class => [ $tester->id => 10 ]
            ])
        ]);

        $this->assertDatabaseHas(ConveyorCell::class, [
            'key' => 'one.two.',
            'value' => 5,
            'models' => json_encode([
                Tester::class => [ $tester->id => 5 ]
            ])
        ]);
    }

    public function test_updating(): void
    {
        $frame = new ConveyorFrame();
        $frame->base_key = 'key';
        $frame->key = 'key';
        $frame->params = [];
        $frame->save();

        $tester = new Tester();
        $tester->save();

        $first = new ConveyorCell();
        $first->conveyor_frame_id = $frame->id;
        $first->key = 'one.one.';
        $first->value = 1;
        $first->models = [ Tester::class => [ $tester->id => 1 ] ];
        $frame->cells()->save($first);

        $second = new ConveyorCell();
        $second->conveyor_frame_id = $frame->id;
        $second->key = 'one.two.';
        $second->value = 1;
        $second->models = [ Tester::class => [ $tester->id => 1 ] ];
        $frame->cells()->save($second);

        Cell::$currentModel = $tester;

        $cells = new Cells($frame);

        $cells->get('one', 'one')->change(10);
        $cells->get('one', 'one')->change(5);
        $cells->get('one', 'two')->change(5);

        $cells->save();

        $this->assertDatabaseHas(ConveyorCell::class, [
            'key' => 'one.one.',
            'value' => 15,
            'models' => json_encode([
                Tester::class => [ $tester->id => 15 ]
            ])
        ]);

        $this->assertDatabaseHas(ConveyorCell::class, [
            'key' => 'one.two.',
            'value' => 5,
            'models' => json_encode([
                Tester::class => [ $tester->id => 5 ]
            ])
        ]);
    }
}
