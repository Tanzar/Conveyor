<?php

namespace Tanzar\Conveyor\Tests;

use Tanzar\Conveyor\ConveyorServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{

    
    public function setUp(): void
    {
        parent::setUp();

        $first = require __DIR__ . '/../database/migrations/0000_00_00_00_00_01_create_conveyor_frames_table.php';
        $first->up();
        
        $second = require __DIR__ . '/../database/migrations/0000_00_00_00_00_02_create_conveyor_cells_table.php';
        $second->up();
        
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
        $this->artisan('migrate', ['--database' => 'testing'])->run();
    }
  
    protected function getPackageProviders($app)
    {
        return [
            ConveyorServiceProvider::class,
        ];
    }
  
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testing');
    }
}
