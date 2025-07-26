<?php

namespace Tanzar\Conveyor\Tests\Feature;

use Tanzar\Conveyor\Models\ConveyorFrame;
use Tanzar\Conveyor\Tests\Classes\TableExample;
use Tanzar\Conveyor\Tests\TestCase;

class ConveyorRouteTest extends TestCase
{

    public function test_accessing(): void
    {
        $frame = new ConveyorFrame();
        $frame->key = TableExample::class . '-variant=all;';
        $frame->base_key = TableExample::class;
        $frame->params = [ "variant" => "all" ];
        $frame->save();

        $response = $this->get(route('conveyor', [
            'key' => TableExample::class,
            "variant" => "all"
        ]));

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

        $response->assertOk()
            ->assertExactJson($expected);
    }

    public function test_conveyor_join(): void
    {
        $frame = new ConveyorFrame();
        $frame->key = TableExample::class . '-variant=all;';
        $frame->base_key = TableExample::class;
        $frame->params = [ "variant" => "all" ];
        $frame->save();

        $response = $this->get(route('conveyor.join', [
            'key' => TableExample::class,
            "variant" => "all"
        ]));

        $expected = [
            'channel' => TableExample::class . '-variant=all;',
            'state' => [
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
            ]
        ];

        $response->assertOk()
            ->assertExactJson($expected);
    }
}
