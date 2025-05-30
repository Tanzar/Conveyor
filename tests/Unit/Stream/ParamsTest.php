<?php


namespace Tanzar\Conveyor\Tests\Unit\Stream;

use Illuminate\Support\Carbon;
use Tanzar\Conveyor\Params\StreamParams;
use Tanzar\Conveyor\Tests\TestCase;

class ParamsTest extends TestCase
{
	public function test_values(): void
    {
        $values = [
            'int' => 1,
            'string' => 'Test string',
            'date' => '2025-01-01 12:13:14'
        ];

        $params = new StreamParams($values);

        $this->assertEquals(1, $params->int('int'));
        $this->assertEquals('Test string', $params->string('string'));

        $date = $params->date('date')->format('Y-m-d H:i:s');
        $this->assertEquals('2025-01-01 12:13:14', $date);

        $this->assertEquals($values, $params->toArray());
    }

    public function test_default_values(): void
    {
        Carbon::setTestNow('2025-01-01 12:13:14');

        $params = new StreamParams([]);

        $this->assertEquals(0, $params->int('int'));
        $this->assertEquals('', $params->string('string'));

        $date = $params->date('date')->format('Y-m-d H:i:s');
        $this->assertEquals('2025-01-01 12:13:14', $date);
    }
}