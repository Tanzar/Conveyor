<?php

namespace Tanzar\Conveyor\Tests;

use Orchestra\Testbench\TestCase as TestbenchTestCase;
use Tanzar\Conveyor\ConveyorServiceProvider;

class TestCase extends TestbenchTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        // additional setup
    }
  
    protected function getPackageProviders($app)
    {
        return [
            ConveyorServiceProvider::class,
        ];
    }
  
    protected function getEnvironmentSetUp($app)
    {
        // perform environment setup
    }
}
