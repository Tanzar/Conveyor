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
        $frame->key = 'table-date=2025-07-01;';
        $frame->params = [ 'date' => '2025-07-01' ];
        $frame->save();

        $utilFrame = ConveyorUtils::findFrame('table', [ 'date' => '2025-07-01' ]);

        $this->assertEquals($frame->id, $utilFrame->id);
        $this->assertEquals($frame->key, $utilFrame->key);
    }

    public function test_find_frame_exception(): void
    {
        $this->expectException(UninitializedConveyorException::class);
        
        config()->set('conveyor.keys', [ 'table' => TableExample::class ]);

        ConveyorUtils::findFrame('table', [ 'date' => '2025-07-01' ]);
    }

    public function test_make_core(): void
    {
        config()->set('conveyor.keys', [ 'table' => TableExample::class ]);
        
        $core = ConveyorUtils::makeCore('table');

        $this->assertEquals(TableExample::class, $core::class);
    }
}
