<?php

namespace Tanzar\Conveyor\Tests\Unit;

use Tanzar\Conveyor\Exceptions\ConveyorException;
use Tanzar\Conveyor\Grinder\GrinderSetup;
use Tanzar\Conveyor\Tests\TestCase;

class GrinderSetupTest extends TestCase
{

    public function test_class_methods(): void
    {
        $setup = new GrinderSetup();

        $first = fn()=> 5;

        $setup->add('first', $first);

        $this->assertEquals(['first'], $setup->keys());

        $this->assertEquals($first, $setup->get('first'));
    }

    public function test_class_exception(): void
    {
        $setup = new GrinderSetup();

        $this->expectException(ConveyorException::class);

        $setup->get('notFound');
    }
}
