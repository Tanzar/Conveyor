<?php

namespace Tanzar\Conveyor\Tests\Unit\Helpers;

use Illuminate\Support\Facades\Queue;
use Tanzar\Conveyor\Helpers\ConveyorUpdateByKeyHelper;
use Tanzar\Conveyor\Jobs\ConveyorRunJob;
use Tanzar\Conveyor\Models\ConveyorCell;
use Tanzar\Conveyor\Models\ConveyorFrame;
use Tanzar\Conveyor\Tests\Classes\TableExample;
use Tanzar\Conveyor\Tests\Models\Food;
use Tanzar\Conveyor\Tests\TestCase;

class ConveyorUpdateHelpersTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Food::factory()->count(5)->create([
            'type' => 'pizza',
            'day' => 'today'
        ]);

        Food::factory()->count(10)->create([
            'type' => 'pizza',
            'day' => 'yesterday'
        ]);

        Food::factory()->count(2)->create([
            'type' => 'burger',
            'day' => 'today'
        ]);

        Food::factory()->count(7)->create([
            'type' => 'burger',
            'day' => 'yesterday'
        ]);

        $frame = new ConveyorFrame();
        $frame->key = TableExample::class . '-';
        $frame->base_key = TableExample::class;
        $frame->params = [];
        $frame->save();

        
        $first = new ConveyorCell();
        $first->key = 'pizza.today.';
        $first->value = 5.0;
        $first->models = [ Food::class => [ 1 => 1, 2 => 1, 3 => 1, 4 => 1, 5 => 1 ] ];
        $frame->cells()->save($first);
        
        $second = new ConveyorCell();
        $second->key = 'pizza.yesterday.';
        $second->value = 10.0;
        $second->models = [
                Food::class => [ 6 => 1, 7 => 1, 8 => 1, 9 => 1, 10 => 1, 11 => 1, 12 => 1, 13 => 1, 14 => 1, 15 => 1 ]
        ];
        $frame->cells()->save($second);

        $third = new ConveyorCell();
        $third->key = 'burger.today.';
        $third->value = 2.0;
        $third->models = [
                Food::class => [ 16 => 1, 17 => 1 ]
        ];
        $frame->cells()->save($third);

        $fourth = new ConveyorCell();
        $fourth->key = 'burger.yesterday.';
        $fourth->value = 7.0;
        $fourth->models = [
                Food::class => [ 18 => 1, 19 => 1, 20 => 1, 21 => 1, 22 => 1, 23 => 1, 24 => 1 ]
        ];
        $frame->cells()->save($fourth);
    }

    public function test_by_key_params_dispatch(): void
    {
        Queue::fake();

        /** @var Food $model */
        $model = Food::find(19);
        $model->type = 'pizza';
        $model->day = 'today';
        $model->save();

        $helper = new ConveyorUpdateByKeyHelper(TableExample::class);

        $helper->params([]);

        $queue = 'conveyor:' . ConveyorFrame::first()->key;

        Queue::assertPushedOn($queue, ConveyorRunJob::class);
        Queue::assertPushed(ConveyorRunJob::class, 1);
    }

    public function test_by_key_params_dispatch_false(): void
    {
        Queue::fake();

        /** @var Food $model */
        $model = Food::find(19);
        $model->type = 'pizza';
        $model->day = 'today';
        $model->save();

        $helper = new ConveyorUpdateByKeyHelper(TableExample::class);

        $helper->params([], false);

        Queue::assertNotPushed(ConveyorRunJob::class);
    }
    
    public function test_by_key_model_dispatch(): void
    {
        Queue::fake();

        /** @var Food $model */
        $model = Food::find(19);
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

        /** @var Food $model */
        $model = Food::find(19);
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


}
