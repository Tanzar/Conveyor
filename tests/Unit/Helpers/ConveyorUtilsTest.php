<?php

namespace Tanzar\Conveyor\Tests\Unit\Helpers;

use Tanzar\Conveyor\Exceptions\UninitializedConveyorException;
use Tanzar\Conveyor\Helpers\ConveyorUtils;
use Tanzar\Conveyor\Models\ConveyorFrame;
use Tanzar\Conveyor\Tests\Classes\TableExample;
use Tanzar\Conveyor\Tests\TestCase;

class ConveyorUtilsTest extends TestCase
{
    public function test_find_frame(): void
    {
        config()->set('conveyor.keys', [ 'table' => TableExample::class ]);

        $frame = new ConveyorFrame();
        $frame->base_key = 'table';
        $frame->key = 'table-variant=all;';
        $frame->params = [ 'variant' => 'all' ];
        $frame->save();

        $utilFrame = ConveyorUtils::findFrame('table', [ 'variant' => 'all' ]);

        $this->assertEquals($frame->id, $utilFrame->id);
        $this->assertEquals($frame->key, $utilFrame->key);
    }

    public function test_find_frame_exception(): void
    {
        $this->expectException(UninitializedConveyorException::class);
        
        config()->set('conveyor.keys', [ 'table' => TableExample::class ]);

        ConveyorUtils::findFrame('table', [ 'variant' => 'all' ]);
    }

    public function test_find_frame_with_auto_init(): void
    {
        config()->set('conveyor.autoInit', true);

        $frame = ConveyorUtils::findFrame(TableExample::class, [ 'variant' => 'all' ]);

        $this->assertEquals(1, $frame->id);
        $this->assertEquals(TableExample::class, $frame->base_key);
        $this->assertEquals(TableExample::class . '-variant=all;', $frame->key);
        
        $this->assertDatabaseHas(ConveyorFrame::class, [
            'key' => TableExample::class . '-variant=all;',
            'base_key' => TableExample::class,
            'params' => '{"variant":"all"}'
        ]);
    
    }

    public function test_make_core(): void
    {
        config()->set('conveyor.keys', [ 'table' => TableExample::class ]);
        
        $core = ConveyorUtils::makeCore('table');

        $this->assertEquals(TableExample::class, $core::class);
    }
    
    public function test_init(): void
    {
        ConveyorUtils::init(TableExample::class, [ 'variant' => 'all' ]);

        $this->assertDatabaseHas(ConveyorFrame::class, [
            'key' => TableExample::class . '-variant=all;',
            'base_key' => TableExample::class,
            'params' => '{"variant":"all"}'
        ]);
    
        //Second call
        ConveyorUtils::init(TableExample::class, [ 'variant' => 'all' ]);

        $this->assertDatabaseCount(ConveyorFrame::class, 1);
    }
}
