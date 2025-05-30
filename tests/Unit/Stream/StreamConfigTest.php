<?php

namespace Tanzar\Conveyor\Tests\Unit\Stream;

use Tanzar\Conveyor\Exceptions\InvalidModelException;
use Tanzar\Conveyor\Stream\StreamConfig;
use Tanzar\Conveyor\Tests\Models\Tester;
use Tanzar\Conveyor\Tests\TestCase;

class StreamConfigTest extends TestCase
{
    public function test_models_array(): void
    {
        $config = new StreamConfig();

        $config->add(Tester::class, function() {

        });

        $expected = [ Tester::class ];

        $this->assertEquals($expected, $config->models());
    }

    public function test_handlers_array(): void
    {
        $config = new StreamConfig();

        $val = 0;

        $config->add(Tester::class, function($val) {
            return $val + 5;
        });

        $config->add(Tester::class, function($val) {
            return 5 * $val;
        });

        foreach ($config->handlers(Tester::class) as $handler) {
            $val = $handler($val);
        }

        $this->assertEquals(25, $val);
    }

    public function test_exception_on_add(): void
    {
        $this->expectException(InvalidModelException::class);

        $config = new StreamConfig();

        $config->add(StreamConfig::class, function() {

        });
    }
}
