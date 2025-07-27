<?php

namespace Tanzar\Conveyor\Tests\Unit\Traits;

use Tanzar\Conveyor\Models\ConveyorCell;
use Tanzar\Conveyor\Models\ConveyorFrame;
use Tanzar\Conveyor\Tests\Classes\TableTraitExample;
use Tanzar\Conveyor\Tests\Models\FoodWithTrait;
use Tanzar\Conveyor\Tests\TestCase;

class UseConveyorTraitTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        FoodWithTrait::factory()->count(5)->create([
            'type' => 'pizza',
            'day' => 'today'
        ]);

        FoodWithTrait::factory()->count(10)->create([
            'type' => 'pizza',
            'day' => 'yesterday'
        ]);

        FoodWithTrait::factory()->count(2)->create([
            'type' => 'burger',
            'day' => 'today'
        ]);

        FoodWithTrait::factory()->count(7)->create([
            'type' => 'burger',
            'day' => 'yesterday'
        ]);

        
        config()->set('conveyor.keys', [ 'table' => TableTraitExample::class ]);
        
        $frame = new ConveyorFrame();
        $frame->key = 'table-';
        $frame->base_key = 'table';
        $frame->params = [];
        $frame->save();

        $first = new ConveyorCell();
        $first->key = 'pizza.today.';
        $first->value = 5.0;
        $first->models = [ FoodWithTrait::class => [ 1 => 1, 2 => 1, 3 => 1, 4 => 1, 5 => 1 ] ];
        $frame->cells()->save($first);
        
        $second = new ConveyorCell();
        $second->key = 'pizza.yesterday.';
        $second->value = 10.0;
        $second->models = [
                FoodWithTrait::class => [ 6 => 1, 7 => 1, 8 => 1, 9 => 1, 10 => 1, 11 => 1, 12 => 1, 13 => 1, 14 => 1, 15 => 1 ]
        ];
        $frame->cells()->save($second);

        $third = new ConveyorCell();
        $third->key = 'burger.today.';
        $third->value = 2.0;
        $third->models = [
                FoodWithTrait::class => [ 16 => 1, 17 => 1 ]
        ];
        $frame->cells()->save($third);

        $fourth = new ConveyorCell();
        $fourth->key = 'burger.yesterday.';
        $fourth->value = 7.0;
        $fourth->models = [
                FoodWithTrait::class => [ 18 => 1, 19 => 1, 20 => 1, 21 => 1, 22 => 1, 23 => 1, 24 => 1 ]
        ];
        $frame->cells()->save($fourth);
    }

    public function test_created_event(): void
    {
        $model = new FoodWithTrait();
        $model->type = 'pizza';
        $model->day = 'today';
        $model->save();

        $this->assertDatabaseHas(ConveyorCell::class, [
            'key' => 'pizza.today.',
            'value' => 6.0,
            'models' => json_encode([
                FoodWithTrait::class => [ 1 => 1, 2 => 1, 3 => 1, 4 => 1, 5 => 1, 25 => 1 ]
            ]),
        ]);
    }

    public function test_updated_event(): void
    {
        $model = FoodWithTrait::find(19);
        $model->type = 'pizza';
        $model->day = 'today';
        $model->save();

        $this->assertDatabaseHas(ConveyorCell::class, [
            'key' => 'pizza.today.',
            'value' => 6.0,
            'models' => json_encode([
                FoodWithTrait::class => [ 1 => 1, 2 => 1, 3 => 1, 4 => 1, 5 => 1, 19 => 1 ]
            ]),
        ]);

        $this->assertDatabaseHas(ConveyorCell::class, [
            'key' => 'burger.yesterday.',
            'value' => 6.0,
            'models' => json_encode([
                FoodWithTrait::class => [ 18 => 1, 20 => 1, 21 => 1, 22 => 1, 23 => 1, 24 => 1 ]
            ]),
        ]);
    }

    public function test_deleted_and_restored_events(): void
    {
        FoodWithTrait::find(19)->delete();

        $this->assertDatabaseHas(ConveyorCell::class, [
            'key' => 'burger.yesterday.',
            'value' => 6.0,
            'models' => json_encode([
                FoodWithTrait::class => [ 18 => 1, 20 => 1, 21 => 1, 22 => 1, 23 => 1, 24 => 1 ]
            ]),
        ]);

        FoodWithTrait::withTrashed()->find(19)->restore();

        $this->assertDatabaseHas(ConveyorCell::class, [
            'key' => 'burger.yesterday.',
            'value' => 7.0,
            'models' => json_encode([
                FoodWithTrait::class => [ 18 => 1, 20 => 1, 21 => 1, 22 => 1, 23 => 1, 24 => 1, 19 => 1 ]
            ]),
        ]);
    }

    public function test_force_deleted_event(): void
    {
        FoodWithTrait::find(19)->forceDelete();

        $this->assertDatabaseHas(ConveyorCell::class, [
            'key' => 'burger.yesterday.',
            'value' => 6.0,
            'models' => json_encode([
                FoodWithTrait::class => [ 18 => 1, 20 => 1, 21 => 1, 22 => 1, 23 => 1, 24 => 1 ]
            ]),
        ]);
    }
}
