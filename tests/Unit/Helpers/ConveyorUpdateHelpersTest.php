<?php

namespace Tanzar\Conveyor\Tests\Unit\Helpers;

use Illuminate\Support\Facades\Queue;
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

        $helper = new ConveyorUpdateByKeyHelper(TableExample::class);

        $helper->params([]);

        $queue = 'conveyor:' . ConveyorFrame::first()->key;

        Queue::assertPushedOn($queue, ConveyorRunJob::class);
        Queue::assertPushed(ConveyorRunJob::class, 1);
    }

    public function test_by_key_params_dispatch_false(): void
    {
        Queue::fake();

        $helper = new ConveyorUpdateByKeyHelper(TableExample::class);

        $helper->params([], false);

        Queue::assertNotPushed(ConveyorRunJob::class);
    }
    
    public function test_by_key_model_dispatch(): void
    {
        Queue::fake();

        $model = new Food();
        $model->type = 'pizza';
        $model->day = 'today';
        $model->save();

        $helper = new ConveyorUpdateByKeyHelper(TableExample::class);

        $helper->model($model);

        $queue = 'conveyor:' . ConveyorFrame::first()->key;

        Queue::assertPushedOn($queue, ConveyorRunJob::class);
        Queue::assertPushed(ConveyorRunJob::class, 1);
    }

    public function test_by_key_model_dispatch_false(): void
    {
        Queue::fake();

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
        $helper = new ConveyorUpdateByKeyHelper(TableExample::class);

        $helper->all();

        $queue = 'conveyor:' . ConveyorFrame::first()->key;

        Queue::assertPushedOn($queue, ConveyorRunJob::class);
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

        $model = new Food();
        $model->type = 'pizza';
        $model->day = 'today';
        $model->save();

        $helper = new ConveyorUpdateHelper();

        $helper->model($model);

        $queue = 'conveyor:' . ConveyorFrame::first()->key;

        Queue::assertPushedOn($queue, ConveyorRunJob::class);
        Queue::assertPushed(ConveyorRunJob::class, 1);
    }

    public function test_model_dispatch_false(): void
    {
        Queue::fake();

        $model = new Food();
        $model->type = 'pizza';
        $model->day = 'today';
        $model->save();

        $helper = new ConveyorUpdateHelper();

        $helper->model($model, false);

        Queue::assertNotPushed(ConveyorRunJob::class);
    }

    public function test_all_dispatch(): void
    {
        Queue::fake();

        $helper = new ConveyorUpdateHelper();

        $helper->all();

        $queue = 'conveyor:' . ConveyorFrame::first()->key;

        Queue::assertPushedOn($queue, ConveyorRunJob::class);
        Queue::assertPushed(ConveyorRunJob::class, 1);
    }

    public function test_all_dispatch_false(): void
    {
        Queue::fake();

        $helper = new ConveyorUpdateHelper();

        $helper->all(false);

        Queue::assertNotPushed(ConveyorRunJob::class);
    }

}
