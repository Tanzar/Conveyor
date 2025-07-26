<?php

namespace Tanzar\Conveyor\Tests\Unit\Helpers;

use Tanzar\Conveyor\Exceptions\UnauthorizedAccessException;
use Tanzar\Conveyor\Helpers\Conveyor;
use Tanzar\Conveyor\Models\ConveyorFrame;
use Tanzar\Conveyor\Tests\Classes\TableExample;
use Tanzar\Conveyor\Tests\TestCase;

class ConveyorGetTest extends TestCase
{
    public function test_correct_get(): void
    {
        $frame = new ConveyorFrame();
        $frame->key = TableExample::class . '-variant=all;';
        $frame->base_key = TableExample::class;
        $frame->params = [ "variant" => "all" ];
        $frame->save();

        $result = Conveyor::get(TableExample::class, [ "variant" => "all" ]);

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

        $this->assertEquals($expected, $result);
    }

    public function test_access_denied(): void
    {
        $frame = new ConveyorFrame();
        $frame->key = TableExample::class . '-variant=none;';
        $frame->base_key = TableExample::class;
        $frame->params = [ "variant" => "none" ];
        $frame->save();

        $this->expectException(UnauthorizedAccessException::class);

        Conveyor::get(TableExample::class, [ "variant" => "none" ]);

    }
}
