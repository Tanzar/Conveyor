<?php

namespace Tanzar\Conveyor\Tests\Unit\Helpers;

use Tanzar\Conveyor\Helpers\ConveyorInitHelper;
use Tanzar\Conveyor\Models\ConveyorFrame;
use Tanzar\Conveyor\Tests\Classes\TableExample;
use Tanzar\Conveyor\Tests\TestCase;

class ConveyorInitTest extends TestCase
{

    public function test_option(): void
    {
        $helper = new ConveyorInitHelper(TableExample::class);

        $helper->option([ 'variant' => 'all' ]);

        $this->assertDatabaseHas(ConveyorFrame::class, [
            'key' => TableExample::class . '-variant=all;',
            'base_key' => TableExample::class,
            'params' => '{"variant":"all"}'
        ]);
    
        //Second call
        $helper->option([ 'variant' => 'all' ]);

        $this->assertDatabaseCount(ConveyorFrame::class, 1);
    }

    public function test_all(): void
    {
        $helper = new ConveyorInitHelper(TableExample::class);

        $helper->all();

        $this->assertDatabaseHas(ConveyorFrame::class, [
            'key' => TableExample::class . '-variant=pizzas;',
            'base_key' => TableExample::class,
            'params' => '{"variant":"pizzas"}'
        ]);

        $this->assertDatabaseHas(ConveyorFrame::class, [
            'key' => TableExample::class . '-variant=burgers;',
            'base_key' => TableExample::class,
            'params' => '{"variant":"burgers"}'
        ]);

        $this->assertDatabaseHas(ConveyorFrame::class, [
            'key' => TableExample::class . '-variant=all;',
            'base_key' => TableExample::class,
            'params' => '{"variant":"all"}'
        ]);

        $this->assertDatabaseCount(ConveyorFrame::class, 3);

        //Second call
        $helper->all();

        $this->assertDatabaseCount(ConveyorFrame::class, 3);

    }
}
