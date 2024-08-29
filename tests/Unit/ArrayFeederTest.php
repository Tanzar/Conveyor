<?php

namespace Tanzar\Conveyor\Tests\Unit;

use Tanzar\Conveyor\Base\Feeder\ArrayFeeder;
use Tanzar\Conveyor\Tests\TestCase;

class ArrayFeederTest extends TestCase
{
    public function test_with_array_parameter(): void
    {
        $feeder = new ArrayFeeder([10, 10, 10, 20]);

        $sum = 0;
        $feeder->each(function ($value, $key) use (&$sum) {
            $sum += $value;
        });

        $this->assertEquals(50, $sum);
    }

    public function test_with_collection_parameter(): void
    {
        $feeder = new ArrayFeeder(collect([10, 10, 10, 20]));

        $sum = 0;
        $feeder->each(function ($value, $key) use (&$sum) {
            $sum += $value;
        });

        $this->assertEquals(50, $sum);
    }


}
