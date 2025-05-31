<?php

namespace Tanzar\Conveyor\Tests;

use CreateConveyorStreamModels;
use Tanzar\Conveyor\ConveyorServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{

    
    public function setUp(): void
    {
        parent::setUp();

        include_once __DIR__ . '/../database/migrations/create_conveyors_cache_table.php';
        (new \CreateConveyorStreams())->up();

        include_once __DIR__ . '/../database/migrations/create_conveyors_models_cache_table.php';
        (new CreateConveyorStreamModels())->up();
        
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
