<?php

namespace Tanzar\Conveyor\Tests\Unit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use stdClass;
use Tanzar\Conveyor\Base\Feeder\DatabaseFeeder;
use Tanzar\Conveyor\Tests\Models\Tester;
use Tanzar\Conveyor\Tests\TestCase;

class DatabaseFeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_with_eloquent_parameter(): void
    {
        Tester::factory(10)->create();

        $feeder = new DatabaseFeeder(Tester::query());

        $sum = 0;
        $feeder->each(function (Tester $tester) use (&$sum) {
            $sum++;
        });

        $this->assertEquals(10, $sum);
    }

    public function test_with_db_parameter(): void
    {
        Tester::factory(20)->create();

        $feeder = new DatabaseFeeder(DB::query()->from('testers'));

        $sum = 0;
        $feeder->each(function (stdClass $tester) use (&$sum) {
            $sum++;
        });

        $this->assertEquals(20, $sum);
    }
}
