<?php

namespace Tanzar\Conveyor\Tests\Unit;

use Tanzar\Conveyor\Models\ConveyorStreamModel;
use Tanzar\Conveyor\Models\ConveyorStream;
use Tanzar\Conveyor\Tests\TestCase;

class StreamingDatabaseTest extends TestCase
{
    
    public function test_conveyor_streams(): void
    {
        $stream = new ConveyorStream();

        $stream->conveyor_class = 'test::class';
        $stream->key = 'key_one';
        $stream->current_state = [
            'headers' => [],
            'rows' => [],
            'cells' => [],
        ];

        $stream->save();

        $this->assertDatabaseCount('conveyor_streams', 1);

        $streamModel = new ConveyorStreamModel();
        $streamModel->conveyor_stream_id = $stream->id;
        $streamModel->streamable_model_class = 'FakeClass::class';
        $streamModel->streamable_model_id = 1;
        $streamModel->current_state = [
            'cells' => []
        ];
        $streamModel->save();

        $this->assertDatabaseCount('conveyor_stream_models', 1);
    }

}
