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

        $expected = [
            'rows' => [
                [ 'key' => 'burger', 'label' => 'Burgers', 'options' => [] ],
                [ 'key' => 'pizza', 'label' => 'Pizzas', 'options' => [] ],
            ],
            'columns' => [
                [ 'key' => 'total', 'label' => 'Sum', 'options' => [] ],
                [ 'key' => 'today', 'label' => 'Today', 'options' => [] ],
                [ 'key' => 'yesterday', 'label' => 'Yesterday', 'options' => [] ],
            ],
            'cells' => [
                'pizza' => [
                    'today' => 0,
                    'yesterday' => 0,
                    'total' => 0,
                ],
                'burger' => [
                    'today' => 0,
                    'yesterday' => 0,
                    'total' => 0,
                ],
            ],
        ];

        $this->assertEquals($expected, $response);
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
