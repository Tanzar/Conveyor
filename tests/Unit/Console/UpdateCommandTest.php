<?php

namespace Tanzar\Conveyor\Tests\Unit\Console;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use Tanzar\Conveyor\Events\ConveyorUpdated;
use Tanzar\Conveyor\Jobs\ConveyorRunJob;
use Tanzar\Conveyor\Models\ConveyorFrame;
use Tanzar\Conveyor\Tests\Classes\TableExample;
use Tanzar\Conveyor\Tests\Classes\TableTraitExample;
use Tanzar\Conveyor\Tests\Models\Food;
use Tanzar\Conveyor\Tests\TestCase;

class UpdateCommandTest extends TestCase
{

    public function test_command_without_inputs(): void
    {
        Queue::fake();
        Event::fake();
        config()->set('conveyor.queue', 'conveyor');
        config()->set('conveyor.keys.table', TableExample::class);
        config()->set('conveyor.keys.tableTrait', TableTraitExample::class);

        $frame = new ConveyorFrame();
        $frame->key = 'table-variant=all;';
        $frame->base_key = 'table';
        $frame->params = [ "variant" => "all" ];
        $frame->save();

        $frame = new ConveyorFrame();
        $frame->key = 'tableTrait-variant=all;';
        $frame->base_key = 'tableTrait';
        $frame->params = [ "variant" => "all" ];
        $frame->save();

        Artisan::call('conveyor:update');

        Queue::assertPushedOn('conveyor', ConveyorRunJob::class);
        Queue::assertPushed(ConveyorRunJob::class, 2);
    }

    public function test_command_with_now_option(): void
    {
        Queue::fake();
        Event::fake();
        config()->set('conveyor.keys.table', TableExample::class);

        $frame = new ConveyorFrame();
        $frame->key = 'table-variant=all;';
        $frame->base_key = 'table';
        $frame->params = [ "variant" => "all" ];
        $frame->save();

        Artisan::call('conveyor:update --now');

        Queue::assertNotPushed(ConveyorRunJob::class);
    }

    public function test_command_with_key(): void
    {
        Queue::fake();
        Event::fake();
        config()->set('conveyor.queue', 'conveyor');
        config()->set('conveyor.keys.table', TableExample::class);
        config()->set('conveyor.keys.tableTrait', TableTraitExample::class);

        $frame = new ConveyorFrame();
        $frame->key = 'table-variant=all;';
        $frame->base_key = 'table';
        $frame->params = [ "variant" => "all" ];
        $frame->save();

        $frame = new ConveyorFrame();
        $frame->key = 'tableTrait-variant=all;';
        $frame->base_key = 'tableTrait';
        $frame->params = [ "variant" => "all" ];
        $frame->save();

        Artisan::call('conveyor:update table');

        Queue::assertPushedOn('conveyor', ConveyorRunJob::class);
        Queue::assertPushed(ConveyorRunJob::class, 1);
    }

    public function test_command_with_key_and_option(): void
    {
        Queue::fake();
        Event::fake();
        config()->set('conveyor.queue', 'conveyor');
        config()->set('conveyor.keys.table', TableExample::class);
        config()->set('conveyor.keys.tableTrait', TableTraitExample::class);

        $frame = new ConveyorFrame();
        $frame->key = 'table-variant=all;';
        $frame->base_key = 'table';
        $frame->params = [ "variant" => "all" ];
        $frame->save();

        $frame = new ConveyorFrame();
        $frame->key = 'tableTrait-variant=all;';
        $frame->base_key = 'tableTrait';
        $frame->params = [ "variant" => "all" ];
        $frame->save();

        Artisan::call('conveyor:update table --now');

        Queue::assertNotPushed(ConveyorRunJob::class);
    }
}
