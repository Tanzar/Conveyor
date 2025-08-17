<?php

namespace Tanzar\Conveyor\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use ReflectionClass;
use Tanzar\Conveyor\Helpers\Conveyor;
use Tanzar\Conveyor\Models\ConveyorDeployLog;

class DeployCommand extends Command
{
    protected $signature = 'conveyor:deploy {--now : Flag to run update when executing command}';

    protected $description = 'Updates changed conveyor files';

    public function handle()
    {
        $classes = config('conveyor.keys');

        $logs = ConveyorDeployLog::all()->keyBy('class_name');

        foreach($classes as $class) {
            $this->update($logs, $class);
        }
    }

    private function update(Collection $logs, string $class): void
    {
        $log = $logs->get($class);

        $modifiedAt = $this->getModifiedDate($class);

        if ($log === null) {
            $log = new ConveyorDeployLog();
            $log->class_name = $class;
        
            $this->updateLog($log, $modifiedAt);
            return;
        }

        if ($modifiedAt->isAfter($log->modified_at)) {
            $this->updateLog($log, $modifiedAt);
        }
    }

    private function getModifiedDate(string $class): Carbon
    {
        $reflect = new ReflectionClass($class);
        $time = filemtime($reflect->getFileName());
        return Carbon::parse($time);
    }

    private function updateLog(ConveyorDeployLog $log, Carbon $modifiedAt): void
    {
        $log->modified_at = $modifiedAt;
        $log->save();
        
        $now = (bool) $this->option('now');

        Conveyor::init($log->class_name)->all();

        Conveyor::updateByClass($log->class_name)
                ->all(!$now);
    }
}
