<?php

namespace Tanzar\Conveyor\Tests\Unit\Structure;

use Tanzar\Conveyor\Models\ConveyorCellKey;
use Tanzar\Conveyor\Models\ConveyorCellValue;
use Tanzar\Conveyor\Models\ConveyorGrinderKey;
use Tanzar\Conveyor\Models\ConveyorParam;
use Tanzar\Conveyor\Tests\TestCase;

class StructureTest extends TestCase
{

    public function test_relations(): void
    {
        $firstParam = new ConveyorParam();
        $firstParam->name = 'user';
        $firstParam->param_value = 1;
        $firstParam->save();

        $secondParam = new ConveyorParam();
        $secondParam->name = 'group';
        $secondParam->param_value = 3;
        $secondParam->save();

        $cellKey = new ConveyorCellKey();
        $cellKey->name = 'total';
        $cellKey->save();

        $grinderKey = new ConveyorGrinderKey();
        $grinderKey->name = 'transactions';
        $grinderKey->save();

        $cell = new ConveyorCellValue();
        $cell->grinder_key_id = $grinderKey->id;
        $cell->cell_key_id = $cellKey->id;
        $cell->cell_value = 1;
        $cell->save();

        $cell->params()->attach($firstParam);
        $cell->params()->attach($secondParam);

        $this->assertDatabaseCount('conveyor_cell_params', 2);

        $this->assertDatabaseHas('conveyor_cell_params', [
            'cell_id' => $cell->id,
            'param_id' => $firstParam->id,
        ]);
        
        $this->assertDatabaseHas('conveyor_cell_params', [
            'cell_id' => $cell->id,
            'param_id' => $secondParam->id,
        ]);
    }
}
