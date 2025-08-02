<?php

namespace Tanzar\Conveyor\Tests\Unit\Console;

use Illuminate\Support\Facades\Artisan;
use Tanzar\Conveyor\Models\ConveyorFrame;
use Tanzar\Conveyor\Tests\Classes\TableExample;
use Tanzar\Conveyor\Tests\Classes\TableTraitExample;
use Tanzar\Conveyor\Tests\TestCase;

class InitCommandTest extends TestCase
{

    public function test_conveyor_init_command(): void
    {
        config()->set('conveyor.keys.table', TableExample::class);
        config()->set('conveyor.keys.tableTrait', TableTraitExample::class);

        Artisan::call('conveyor:init');

        $this->assertDatabaseHas(ConveyorFrame::class, [
            'base_key' => 'table',
            'key' => 'table-variant=all;'
        ]);

        $this->assertDatabaseHas(ConveyorFrame::class, [
            'base_key' => 'table',
            'key' => 'table-variant=pizzas;'
        ]);

        $this->assertDatabaseHas(ConveyorFrame::class, [
            'base_key' => 'table',
            'key' => 'table-variant=burgers;'
        ]);
        $this->assertDatabaseHas(ConveyorFrame::class, [
            'base_key' => 'tableTrait',
            'key' => 'tableTrait-variant=all;'
        ]);

        $this->assertDatabaseHas(ConveyorFrame::class, [
            'base_key' => 'tableTrait',
            'key' => 'tableTrait-variant=pizzas;'
        ]);

        $this->assertDatabaseHas(ConveyorFrame::class, [
            'base_key' => 'tableTrait',
            'key' => 'tableTrait-variant=burgers;'
        ]);
    }
}
