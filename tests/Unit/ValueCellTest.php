<?php

namespace Tanzar\Conveyor\Tests\Unit;

use Illuminate\Support\Carbon;
use Tanzar\Conveyor\Grinder\Cell\ValueCell;
use Tanzar\Conveyor\Grinder\Params\GrinderParams;
use Tanzar\Conveyor\Models\ConveyorCalculableKey;
use Tanzar\Conveyor\Models\ConveyorCellKey;
use Tanzar\Conveyor\Models\ConveyorCellValue;
use Tanzar\Conveyor\Models\ConveyorGrinderKey;
use Tanzar\Conveyor\Models\ConveyorModelValue;
use Tanzar\Conveyor\Models\ConveyorParam;
use Tanzar\Conveyor\Tests\TestCase;

class ValueCellTest extends TestCase
{

    private ConveyorGrinderKey $grinder;
    private ConveyorCalculableKey $classKey;
    private ConveyorCellKey $firstCellKey;
    private ConveyorParam $dayParam;
    private ConveyorParam $userParam;
    private ConveyorParam $typeParam;

    public function setUp(): void
    {
        parent::setUp();
        
        $this->grinder = new ConveyorGrinderKey();
        $this->grinder->name = 'TestGrinder';
        $this->grinder->save();
        
        $this->firstCellKey = new ConveyorCellKey();
        $this->firstCellKey->name = 'TestCell';
        $this->firstCellKey->save();

        $this->classKey = new ConveyorCalculableKey();
        $this->classKey->model_class_name = 'TestClass';
        $this->classKey->save();

        $this->dayParam = new ConveyorParam();
        $this->dayParam->name = 'day';
        $this->dayParam->param_value = '2026-01-10';
        $this->dayParam->save();

        $this->userParam = new ConveyorParam();
        $this->userParam->name = 'user';
        $this->userParam->param_value = '1';
        $this->userParam->save();

        $this->typeParam = new ConveyorParam();
        $this->typeParam->name = 'type';
        $this->typeParam->param_value = 'new';
        $this->typeParam->save();

    }

    public function test_new_cell(): void
    {
        $modelValue = new ConveyorModelValue();
        $modelValue->calculable_id = [ 'id' => 1 ];
        $modelValue->calculable_key_id = $this->classKey->id;

        $params = new GrinderParams([ 'user', 'type', 'day' ]);

        $cell = new ValueCell(
            $this->grinder,
            $this->firstCellKey,
            $modelValue,
            $params
        );
        $cell->setValue(123);
        $cell->params()
            ->date('day', Carbon::parse('2026-01-12 12:00:00'))
            ->int('user', 4)
            ->string('type', 'old');

        $cell->save();

        $this->assertDatabaseHas('conveyor_cell_values', [
            'grinder_key_id' => $this->grinder->id,
            'cell_key_id' => $this->firstCellKey->id,
            'cell_value' => 123
        ]);

        $this->assertDatabaseHas('conveyor_model_values', [
            'calculable_id' => json_encode([ 'id' => 1 ]),
            'calculable_key_id' => 1,
            'cell_id' => 1,
            'cell_value' => 123
        ]);

        $this->assertDatabaseHas('conveyor_params', [
            'name' => 'day',
            'param_value' => '2026-01-12'
        ]);
        
        $this->assertDatabaseHas('conveyor_params', [
            'name' => 'user',
            'param_value' => 4
        ]);
        
        $this->assertDatabaseHas('conveyor_params', [
            'name' => 'type',
            'param_value' => 'old'
        ]);
    }

    public function test_update_existing_cell_value(): void
    {
        $cellValue = new ConveyorCellValue();
        $cellValue->grinder()->associate($this->grinder);
        $cellValue->cellKey()->associate($this->firstCellKey);
        $cellValue->cell_value = 11;
        $cellValue->save();

        $cellValue->params()->attach($this->dayParam);
        $cellValue->params()->attach($this->userParam);
        $cellValue->params()->attach($this->typeParam);

        $modelValue = new ConveyorModelValue();
        $modelValue->calculable_id = [
            'id' => 1
        ];
        $modelValue->calculable_key_id = $this->classKey->id;
        $modelValue->cell_id = $cellValue->id;
        $modelValue->cell_value = 11;
        $modelValue->save();

        $params = new GrinderParams([ 'user', 'type', 'day' ]);

        $cell = new ValueCell(
            $this->grinder,
            $this->firstCellKey,
            $modelValue,
            $params
        );
        $cell->setValue(123);
        $cell->params()
            ->date('day', Carbon::parse('2026-01-10 12:00:00'))
            ->int('user', 1)
            ->string('type', 'new');

        $cell->save();

        $this->assertDatabaseHas('conveyor_cell_values', [
            'id' => $cellValue->id,
            'grinder_key_id' => $this->grinder->id,
            'cell_key_id' => $this->firstCellKey->id,
            'cell_value' => 123
        ]);

        $this->assertDatabaseHas('conveyor_model_values', [
            'id' => $modelValue->id,
            'calculable_id' => json_encode([ 'id' => 1 ]),
            'calculable_key_id' => $this->classKey->id,
            'cell_id' => $cellValue->id,
            'cell_value' => 123
        ]);
    }
    
    public function test_update_cell_value_params(): void
    {
        $cellValue = new ConveyorCellValue();
        $cellValue->grinder()->associate($this->grinder);
        $cellValue->cellKey()->associate($this->firstCellKey);
        $cellValue->cell_value = 11;
        $cellValue->save();

        $cellValue->params()->attach($this->dayParam);
        $cellValue->params()->attach($this->userParam);
        $cellValue->params()->attach($this->typeParam);

        $modelValue = new ConveyorModelValue();
        $modelValue->calculable_id = [
            'id' => 1
        ];
        $modelValue->calculable_key_id = $this->classKey->id;
        $modelValue->cell_id = $cellValue->id;
        $modelValue->cell_value = 11;
        $modelValue->save();

        $params = new GrinderParams([ 'user', 'type', 'day' ]);

        $cell = new ValueCell(
            $this->grinder,
            $this->firstCellKey,
            $modelValue,
            $params
        );
        $cell->setValue(123);
        $cell->params()
            ->date('day', Carbon::parse('2026-01-10 12:00:00'))
            ->int('user', 1)
            ->string('type', 'old');

        $cell->save();

        $this->assertDatabaseMissing('conveyor_cell_values', [
            'id' => $cellValue->id
        ]);

        $this->assertDatabaseHas('conveyor_model_values', [
            'calculable_id' => json_encode([ 'id' => 1 ]),
            'calculable_key_id' => $this->classKey->id,
            'cell_id' => $cellValue->id + 1,
            'cell_value' => 123
        ]);
        
        $this->assertDatabaseHas('conveyor_cell_values', [
            'id' => $cellValue->id + 1,
            'grinder_key_id' => $this->grinder->id,
            'cell_key_id' => $this->firstCellKey->id,
            'cell_value' => 123
        ]);
    }
}
