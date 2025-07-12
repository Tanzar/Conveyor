<?php

namespace Tanzar\Conveyor\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Tanzar\Conveyor\Helpers\ConveyorUtils;
use Tanzar\Conveyor\Models\ConveyorFrame;

class ConveyorRunJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private ConveyorFrame $frame;

    public function __construct(public int $frameId, public ?Model $model = null)
    {
        $this->frame = ConveyorFrame::findOrFail($frameId);

        $this->onQueue('conveyor:' . $this->frame->key);
    }

    public function handle(): void
    {
        $core = ConveyorUtils::makeCore($this->frame->base_key);

        $core->update($this->frame, $this->model);

    }

    
}