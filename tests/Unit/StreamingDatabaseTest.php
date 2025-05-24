<?php

namespace Tanzar\Conveyor\Tests\Unit;

use Tanzar\Conveyor\Models\ConveyorStreamModel;
use Tanzar\Conveyor\Models\ConveyorStream;
use Tanzar\Conveyor\Tests\Models\Tester;
use Tanzar\Conveyor\Tests\TestCase;

class StreamingDatabaseTest extends TestCase
{
    
    public function test_conveyor_streams(): void
    {
        $stream = new ConveyorStream();

        $stream->base_key = 'key';
        $stream->key = 'key_one';
        $stream->params = [];
        $stream->current_state = [
            'headers' => [],
            'rows' => [],
            'cells' => [],
        ];

        $stream->save();

        $this->assertDatabaseCount('conveyor_streams', 1);

        $testerModel = new Tester();
        $testerModel->save();
        
        $streamModel = new ConveyorStreamModel();
        $streamModel->conveyor_stream_id = $stream->id;
        $streamModel->current_state = [
            'cells' => []
        ];

        $testerModel->conveyors()
            ->save($streamModel);

        $this->assertDatabaseCount('conveyor_stream_models', 1);
        $this->assertDatabaseHas('conveyor_stream_models', [
            'streamable_type' => Tester::class
        ]);
    }

}
