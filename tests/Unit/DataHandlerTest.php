<?php

namespace Tanzar\Conveyor\Tests\Unit;

use Tanzar\Conveyor\Base\Conveyor\DataHandler;
use Tanzar\Conveyor\Base\Feeder\ArrayFeeder;
use Tanzar\Conveyor\Tests\TestCase;

class DataHandlerTest extends TestCase
{

    public function test_basic_behavior(): void
    {
        $feeder = new ArrayFeeder([
            [ 'id' => 1 ],
            [ 'id' => 2 ],
            [ 'id' => 3 ],
        ]);

        $handler = new DataHandler();

        $sum = 0;

        $handler->add($feeder, function(array $item) use (&$sum) {
            $sum += $item['id'];
        });

        $handler->run();

        $this->assertEquals(6, $sum);
    }
}
