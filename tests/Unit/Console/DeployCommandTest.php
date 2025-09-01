<?php

namespace Tanzar\Conveyor\Tests\Unit\Console;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use Tanzar\Conveyor\Jobs\ConveyorRunJob;
use Tanzar\Conveyor\Models\ConveyorDeployLog;
use Tanzar\Conveyor\Tests\Classes\TableExample;
use Tanzar\Conveyor\Tests\Classes\TableTraitExample;
use Tanzar\Conveyor\Tests\TestCase;

class DeployCommandTest extends TestCase
{

    public function test_deploy(): void
    {
        Queue::fake();
        Event::fake();
        config()->set('conveyor.queue', 'conveyor');
        config()->set('conveyor.keys.table', TableExample::class);
        config()->set('conveyor.keys.tableWithTrait', TableTraitExample::class);

        Artisan::call('conveyor:deploy');

        $this->assertDatabaseCount(ConveyorDeployLog::class, 2);

        Queue::assertPushedOn('conveyor', ConveyorRunJob::class);
        Queue::assertPushed(ConveyorRunJob::class, 6);
    }

    public function test_redeploy(): void
    {
        Queue::fake();
        Event::fake();
        config()->set('conveyor.queue', 'conveyor');
        config()->set('conveyor.keys.table', TableExample::class);
        config()->set('conveyor.keys.tableWithTrait', TableTraitExample::class);

        $log = new ConveyorDeployLog();
        $log->class_name = TableExample::class;
        $log->modified_at = Carbon::parse('2025-07-26 16:28:04');
        $log->save();

        $second = new ConveyorDeployLog();
        $second->class_name = TableTraitExample::class;
        $second->modified_at = now()->addHour();
        $second->save();

        Artisan::call('conveyor:deploy');

        $log->refresh();

        $this->assertTrue($log->modified_at->isAfter('2025-07-26 16:28:04'));

        $this->assertDatabaseCount(ConveyorDeployLog::class, 2);

        Queue::assertPushedOn('conveyor', ConveyorRunJob::class);
        Queue::assertPushed(ConveyorRunJob::class, 3);
    }
}
