<?php

namespace Tanzar\Conveyor\Tests\Database\Unit;

use Tanzar\Conveyor\Models\ConveyorCell;
use Tanzar\Conveyor\Models\ConveyorCellModel;
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

        $conveyor->cells()->save($cell);

        $testerModel = new Tester();
        $testerModel->save();

        $cellModel = new ConveyorCellModel();
        $cellModel->conveyor_cell_id = $cell->id;
        $cellModel->value = 10;

        $testerModel->conveyorsCells()
            ->save($cellModel);


        $this->assertDatabaseHas(ConveyorFrame::class, [
            'base_key' => 'key',
            'key' => 'key_one'
        ]);

        $this->assertDatabaseCount(Tester::class, 1);

        $this->assertDatabaseHas(ConveyorCell::class, [
            'key' => 'row.col',
            'hidden' => false,
            'value' => 10,
            'options' => json_encode([ 'marked' => false ])
        ]);

        $this->assertDatabaseHas(ConveyorCellModel::class, [
            'model_type' => Tester::class,
            'value' => 10,
        ]);
    }

}
