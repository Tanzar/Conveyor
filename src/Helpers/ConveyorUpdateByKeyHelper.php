<?php

namespace Tanzar\Conveyor\Helpers;

use Illuminate\Database\Eloquent\Model;
use Tanzar\Conveyor\Core\ConveyorCore;
use Tanzar\Conveyor\Jobs\ConveyorRunJob;
use Tanzar\Conveyor\Models\ConveyorFrame;

final class ConveyorUpdateByKeyHelper
{

    private ConveyorCore $core;

    public function __construct(private ?string $baseKey)
    {
        $this->core = ConveyorUtils::makeCore($baseKey);
    }

    /**
     * Update conveyor with given params
     * 
     * ! Warining !
     * If you disable flag it may take long time depending on your conveyor
     * It is recommended to not change flag to false
     * 
     * @param array $params
     * @param bool $dispatch - choose to dispatch as job or update during request
     * @return ConveyorUpdateByKeyHelper
     */
    public function params(array $params, bool $dispatch = true): self
    {
        $frame = ConveyorUtils::findFrame($this->baseKey, $params);

        if ($dispatch) {
            ConveyorRunJob::dispatch($frame->id);
        } else {
            $this->core->update($frame);
        }
        
        return $this;
    }

    /**
     * Update conveyor with given Model
     * This will check multiple conveyors based on selected core
     * 
     * ! Warining !
     * If you disable flag it may take long time depending on your conveyor
     * It is STRONGLY recommended to not change flag to false
     * 
     * @param Model $model
     * @param bool $dispatch
     * @return ConveyorUpdateByKeyHelper
     */
    public function model(Model $model, bool $dispatch = true): self
    {
        ConveyorFrame::query()
            ->where('base_key', $this->baseKey)
            ->lazyById()
            ->each(function (ConveyorFrame $frame) use ($model, $dispatch) {
                if ($dispatch) {
                    ConveyorRunJob::dispatch($frame->id, $model);
                } else {
                    $this->core->update($frame, $model);
                }
            });
        
        return $this;
    }

    /**
     * Update all conveyors
     * This will check ALL conveyors based on selected core
     * 
     * ! Warining !
     * If you disable flag it may take long time depending on your conveyor
     * It is STRONGLY recommended to not change flag to false
     * 
     * @param bool $dispatch
     * @return ConveyorUpdateByKeyHelper
     */
    public function all(bool $dispatch = true): self
    {
        ConveyorFrame::query()
            ->where('base_key', $this->baseKey)
            ->lazyById()
            ->each(function (ConveyorFrame $frame) use ($dispatch) {
                if ($dispatch) {
                    ConveyorRunJob::dispatch($frame->id);
                } else {
                    $this->core->update($frame);
                }
            });
        
        return $this;
    }
}
