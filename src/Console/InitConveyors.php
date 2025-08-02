<?php

namespace Tanzar\Conveyor\Console;

use Illuminate\Console\Command;
use Tanzar\Conveyor\Helpers\Conveyor;

class InitConveyors extends Command
{
    protected $signature = 'conveyor:init';

    protected $description = 'Initializes Conveyors from config file';

    public function handle()
    {
        $config = config('conveyor.keys');
        $keys = collect($config)->keys()->values();

        $this->info('Initializing Conveyors');

        $this->withProgressBar($keys, function (string $key) {
            Conveyor::init($key)->all();
        });

        $this->info('Conveyors initialized');
    }
}
