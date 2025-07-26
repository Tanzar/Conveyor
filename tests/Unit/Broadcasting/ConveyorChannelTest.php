<?php

namespace Tanzar\Conveyor\Tests\Unit\Broadcasting;

use Tanzar\Conveyor\Broadcasting\ConveyorChannel;
use Tanzar\Conveyor\Models\ConveyorFrame;
use Tanzar\Conveyor\Tests\Classes\TableExample;
use Tanzar\Conveyor\Tests\TestCase;

class ConveyorChannelTest extends TestCase
{

    public function test_channel_join(): void
    {
        $frame = new ConveyorFrame();
        $frame->key = TableExample::class . '-variant=all;';
        $frame->base_key = TableExample::class;
        $frame->params = [ "variant" => "all" ];
        $frame->save();

        $channel = new ConveyorChannel();
        $response = $channel->join('', TableExample::class . '-variant=all;');

        $this->assertTrue($response);
    }

    public function test_channel_join_fail(): void
    {
        $frame = new ConveyorFrame();
        $frame->key = TableExample::class . '-variant=burgers;';
        $frame->base_key = TableExample::class;
        $frame->params = [ "variant" => "burgers" ];
        $frame->save();

        $channel = new ConveyorChannel();
        $response = $channel->join('', TableExample::class . '-variant=burgers;');

        $this->assertFalse($response);
    }
}
