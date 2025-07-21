<?php

namespace Tanzar\Conveyor\Tests\Unit\Helpers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use Tanzar\Conveyor\Events\ConveyorUpdated;
use Tanzar\Conveyor\Helpers\ConveyorUpdateByKeyHelper;
use Tanzar\Conveyor\Helpers\ConveyorUpdateHelper;
use Tanzar\Conveyor\Jobs\ConveyorRunJob;
use Tanzar\Conveyor\Models\ConveyorFrame;
use Tanzar\Conveyor\Tests\Classes\TableExample;
use Tanzar\Conveyor\Tests\Models\Food;
use Tanzar\Conveyor\Tests\TestCase;

class ConveyorUpdateHelpersTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $frame = new ConveyorFrame();
        $frame->key = TableExample::class . '-';
        $frame->base_key = TableExample::class;
        $frame->params = [];
        $frame->save();
    }

    public function test_by_key_params_dispatch(): void
    {
        Queue::fake();
        Event::fake();
        config()->set('conveyor.queue', 'conveyor');

        $frame = new ConveyorFrame();
        $frame->key = TableExample::class . '-variant=all;';
        $frame->base_key = TableExample::class;
        $frame->params = [ "variant" => "all" ];
        $frame->save();

        $helper = new ConveyorUpdateByKeyHelper(TableExample::class);

        $helper->params([ 'variant' => 'all' ]);

        Queue::assertPushedOn('conveyor', ConveyorRunJob::class);
        Queue::assertPushed(ConveyorRunJob::class, 1);
    }

    public function test_by_key_params_dispatch_false(): void
    {
        Queue::fake();
        Event::fake();

        $frame = new ConveyorFrame();
        $frame->key = TableExample::class . '-variant=all;';
        $frame->base_key = TableExample::class;
        $frame->params = [ "variant" => "all" ];
        $frame->save();

        $helper = new ConveyorUpdateByKeyHelper(TableExample::class);

        $helper->params([ 'variant' => 'all' ], false);

        Queue::assertNotPushed(ConveyorRunJob::class);
    }
    
    public function test_by_key_model_dispatch(): void
    {
        Queue::fake();
        Event::fake();
        config()->set('conveyor.queue', 'conveyor');

        $model = new Food();
        $model->type = 'pizza';
        $model->day = 'today';
        $model->save();

        $helper = new ConveyorUpdateByKeyHelper(TableExample::class);

        $helper->model($model);
        
        Queue::assertPushedOn('conveyor', ConveyorRunJob::class);
        Queue::assertPushed(ConveyorRunJob::class, 1);
    }

    public function test_by_key_model_dispatch_false(): void
    {
        Queue::fake();
        Event::fake();

        $model = new Food();
        $model->type = 'pizza';
        $model->day = 'today';
        $model->save();

        $helper = new ConveyorUpdateByKeyHelper(TableExample::class);

        $helper->model($model, false);

        Queue::assertNotPushed(ConveyorRunJob::class);
    }

    public function test_by_key_all_dispatch(): void
    {
        Queue::fake();
        config()->set('conveyor.queue', 'conveyor');

        $helper = new ConveyorUpdateByKeyHelper(TableExample::class);
        $helper->all();

        Queue::assertPushedOn('conveyor', ConveyorRunJob::class);
        Queue::assertPushed(ConveyorRunJob::class, 1);
    }

    public function test_by_key_all_dispatch_false(): void
    {
        Queue::fake();

        $helper = new ConveyorUpdateByKeyHelper(TableExample::class);

        $helper->all(false);

        Queue::assertNotPushed(ConveyorRunJob::class);
    }

    public function test_model_dispatch(): void
    {
        Queue::fake();
        config()->set('conveyor.queue', 'conveyor');

        $model = new Food();
        $model->type = 'pizza';
        $model->day = 'today';
        $model->save();

        $helper = new ConveyorUpdateHelper();

        $helper->model($model);

        Queue::assertPushedOn('conveyor', ConveyorRunJob::class);
        Queue::assertPushed(ConveyorRunJob::class, 1);
    }

    public function test_model_dispatch_false(): void
    {
        Queue::fake();
        Event::fake();

        $model = new Food();
        $model->type = 'pizza';
        $model->day = 'today';
        $model->save();

        $helper = new ConveyorUpdateHelper();

        $helper->model($model, false);

        Queue::assertNotPushed(ConveyorRunJob::class);
        Event::assertDispatched(ConveyorUpdated::class);
    }

    public function test_all_dispatch(): void
    {
        Queue::fake();
        config()->set('conveyor.queue', 'conveyor');

        $helper = new ConveyorUpdateHelper();

        $helper->all();

        Queue::assertPushedOn('conveyor', ConveyorRunJob::class);
        Queue::assertPushed(ConveyorRunJob::class, 1);
    }

    public function test_all_dispatch_false(): void
    {
        Queue::fake();
        Event::fake();

        $helper = new ConveyorUpdateHelper();

        $helper->all(false);

        Queue::assertNotPushed(ConveyorRunJob::class);
        Event::assertDispatched(ConveyorUpdated::class);
    }

}
