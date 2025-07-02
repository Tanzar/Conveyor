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

        $helper->option([ 'day' => 'now' ]);

        $this->assertDatabaseHas(ConveyorFrame::class, [
            'key' => TableExample::class . '-',
            'base_key' => TableExample::class,
            'params' => '[]'
        ]);
    
        //Second call
        $helper->option([ 'day' => 'now' ]);

        $this->assertDatabaseCount(ConveyorFrame::class, 1);
    }

    public function test_all(): void
    {
        $helper = new ConveyorInitHelper(TableExample::class);

        $helper->all();

        $this->assertDatabaseHas(ConveyorFrame::class, [
            'key' => TableExample::class . '-',
            'base_key' => TableExample::class,
            'params' => '[]'
        ]);
        $this->assertDatabaseCount(ConveyorFrame::class, 1);

        //Second call
        $helper->all();

        $this->assertDatabaseHas(ConveyorFrame::class, [
            'key' => TableExample::class . '-',
            'base_key' => TableExample::class,
            'params' => '[]'
        ]);
        $this->assertDatabaseCount(ConveyorFrame::class, 1);

    }
}
