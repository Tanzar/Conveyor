<?php

namespace Tanzar\Conveyor\Tests;

use CreateConveyorCells;
use CreateConveyorFrames;
use Tanzar\Conveyor\ConveyorServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{

    
    public function setUp(): void
    {
        parent::setUp();

        include_once __DIR__ . '/../database/migrations/0000_00_00_00_00_01_create_conveyor_frames_table.php';
        (new CreateConveyorFrames())->up();

        include_once __DIR__ . '/../database/migrations/0000_00_00_00_00_02_create_conveyor_cells_table.php';
        (new CreateConveyorCells())->up();

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
