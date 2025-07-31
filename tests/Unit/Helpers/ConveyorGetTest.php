<?php

namespace Tanzar\Conveyor\Tests\Unit\Helpers;

use Tanzar\Conveyor\Exceptions\CellNotExistException;
use Tanzar\Conveyor\Exceptions\InvalidClassException;
use Tanzar\Conveyor\Exceptions\UnauthorizedAccessException;
use Tanzar\Conveyor\Helpers\Conveyor;
use Tanzar\Conveyor\Models\ConveyorCell;
use Tanzar\Conveyor\Models\ConveyorFrame;
use Tanzar\Conveyor\Tests\Classes\TableExample;
use Tanzar\Conveyor\Tests\Models\Food;
use Tanzar\Conveyor\Tests\Models\Tester;
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

        $result = Conveyor::get(TableExample::class, [ "variant" => "all" ])->data();

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

        Conveyor::get(TableExample::class, [ "variant" => "none" ])->data();

    }

    public function test_correct_cell(): void
    {
        $frame = new ConveyorFrame();
        $frame->key = TableExample::class . '-variant=all;';
        $frame->base_key = TableExample::class;
        $frame->params = [ "variant" => "all" ];
        $frame->save();

        $cell = new ConveyorCell();
        $cell->key = 'pizza.today.';
        $cell->value = 5.0;
        $cell->models = [ Food::class => [ 1 => 1, 2 => 1, 3 => 1, 4 => 1, 5 => 1 ] ];
        $frame->cells()->save($cell);
        
        $this->assertEquals(
            [ 1, 2, 3, 4, 5 ],
            Conveyor::get(TableExample::class, [ "variant" => "all" ])->cell(Food::class, ['pizza', 'today'])
        );
    }

    public function test_accesing_not_existing_cell(): void
    {
        $frame = new ConveyorFrame();
        $frame->key = TableExample::class . '-variant=all;';
        $frame->base_key = TableExample::class;
        $frame->params = [ "variant" => "all" ];
        $frame->save();

        $this->expectException(CellNotExistException::class);

        Conveyor::get(TableExample::class, [ "variant" => "all" ])
            ->cell(Food::class, ['macaroni', 'tomato']);
    }

    public function test_accessing_not_set_model(): void
    {
        $frame = new ConveyorFrame();
        $frame->key = TableExample::class . '-variant=all;';
        $frame->base_key = TableExample::class;
        $frame->params = [ "variant" => "all" ];
        $frame->save();

        $this->expectException(InvalidClassException::class);

        Conveyor::get(TableExample::class, [ "variant" => "all" ])
            ->cell(Tester::class, ['macaroni', 'tomato']);
    }
}
