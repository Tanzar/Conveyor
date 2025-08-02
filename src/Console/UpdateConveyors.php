<?php

namespace Tanzar\Conveyor\Console;

use Illuminate\Console\Command;
use Tanzar\Conveyor\Helpers\Conveyor;

class UpdateConveyors extends Command
{
protected $signature = 'conveyor:update {key? : Conveyor key from config or class namespace, if not given update rund for all conveyors from config} {--now : Flag to run update when executing command}';

    protected $description = 'Updates Conveyors based on given parameters, WARNING: when updating all it may take a while';

    public function handle()
    {
        $now = (bool) $this->option('now');

        $key = $this->argument('key');

        $this->info($now ? 'Updating conveyors...' : 'Initializing update jobs...');
        
        if ($key !== null) {

            Conveyor::updateByClass($key)
                ->all(!$now);
        } else {
            Conveyor::update()->all(!$now);
        }

        $this->info('Done');
    }
}
