<?php

namespace Tanzar\Conveyor\Helpers;

use Illuminate\Database\Eloquent\Model;
use Tanzar\Conveyor\Jobs\ConveyorRunJob;
use Tanzar\Conveyor\Models\ConveyorFrame;

final class ConveyorUpdateHelper
{

    /**
     * Update conveyor with given Model
     * This will update ALL conveyors
     * 
     * ! Warining !
     * If you disable flag it may take VERY long time depending on your conveyor
     * It is STRONGLY recommended to not change flag to false
     * 
     * @param Model $model
     * @param bool $dispatch
     * @return ConveyorUpdateByKeyHelper
     */
    public function model(Model $model, bool $dispatch = true): self
    {
        ConveyorFrame::query()
            ->lazyById()
            ->each(function (ConveyorFrame $frame) use ($model, $dispatch) {
                if ($dispatch) {
                    ConveyorRunJob::dispatch($frame->id, $model);
                } else {
                    $core = ConveyorUtils::makeCore($frame->base_key);
                    $core->run($frame, $model);
                }
            });
        
        return $this;
    }

    /**
     * Update all conveyors
     * This will update ALL conveyors
     * 
     * ! Warining !
     * If you disable flag it may take VERY VERY long time depending on your conveyor
     * It is STRONGLY recommended to not change flag to false
     * 
     * @param bool $dispatch
     * @return ConveyorUpdateByKeyHelper
     */
    public function all(bool $dispatch = true): self
    {
        ConveyorFrame::query()
            ->lazyById()
            ->each(function (ConveyorFrame $frame) use ($dispatch) {
                if ($dispatch) {
                    ConveyorRunJob::dispatch($frame->id);
                } else {
                    $core = ConveyorUtils::makeCore($frame->base_key);
                    $core->run($frame);
                }
            });
        
        return $this;
    }
}
