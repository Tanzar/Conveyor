<?php

namespace Tanzar\Conveyor\Tests\Unit\Configs;

use Tanzar\Conveyor\Configs\ConveyorConfig;
use Tanzar\Conveyor\Tests\Models\Tester;
use Tanzar\Conveyor\Tests\TestCase;

class ConveyorConfigTest extends TestCase
{

    public function test_models(): void
    {
        $config = new ConveyorConfig();

        $modelConfig = $config->model(Tester::class);

        $array = $config->toArray();

        $this->assertArrayHasKey(Tester::class, $array);
        $this->assertEquals($modelConfig, $array[Tester::class]);
    }
}
