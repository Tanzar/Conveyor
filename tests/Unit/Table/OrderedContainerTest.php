<?php

namespace Tanzar\Conveyor\Tests\Unit\Table;

use Tanzar\Conveyor\Table\Frame\OrderedContainer;
use Tanzar\Conveyor\Tests\TestCase;

class OrderedContainerTest extends TestCase
{

    public function test_add_after(): void
    {
        $container = new OrderedContainer();

        $container->add('first', 'First');
        $container->add('third', 'third');
        $container->addAfter('first', 'second', 'second');

        $expected = ['First', 'second', 'third'];

        $this->assertEquals($expected, $container->toArray());
    }

    public function test_add_after_when_key_not_exist(): void
    {
        $container = new OrderedContainer();

        $container->add('first', 'First');
        $container->add('third', 'third');
        $container->addAfter('none', 'second', 'second');

        $expected = ['First', 'third', 'second'];

        $this->assertEquals($expected, $container->toArray());
    }

    public function test_add_before(): void
    {
        $container = new OrderedContainer();

        $container->add('first', 'First');
        $container->add('third', 'third');
        $container->addBefore('third', 'second', 'second');

        $expected = ['First', 'second', 'third'];

        $this->assertEquals($expected, $container->toArray());
    }

    public function test_add_before_when_key_not_exist(): void
    {
        $container = new OrderedContainer();

        $container->add('first', 'First');
        $container->add('third', 'third');
        $container->addBefore('none', 'second', 'second');

        $expected = ['First', 'third', 'second'];

        $this->assertEquals($expected, $container->toArray());
    }

}
