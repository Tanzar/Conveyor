<?php

namespace Tanzar\Conveyor\Tests;

use Orchestra\Testbench\TestCase as TestbenchTestCase;
use Tanzar\Conveyor\ConveyorServiceProvider;

class TestCase extends TestbenchTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
        $this->artisan('migrate', ['--database' => 'testbench'])->run();
    }
  
    protected function getPackageProviders($app)
    {
        return [
            ConveyorServiceProvider::class,
        ];
    }
  
    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }
}
