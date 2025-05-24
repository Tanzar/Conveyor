<?php


namespace Tanzar\Conveyor\Stream;

use Illuminate\Database\Eloquent\Model;
use Tanzar\Conveyor\Models\ConveyorStream;
use Tanzar\Conveyor\Stream\Model\StreamableModel;
use Tanzar\Conveyor\Stream\Params\StreamParams;
use Tanzar\Conveyor\Stream\Params\StreamParamsConfig;

class StreamableConveyor
{
    private StreamParams $params;
    
    public function __construct(
        private ConveyorStream $stream
    ) {


        $this->params = new StreamParams($stream->params);
    }

    /**
     * Setup for params
     * Options set in resulting object will be used to generate streams with command
     * @return StreamParamsConfig
     */
    public static function getParamsConfig(): StreamParamsConfig
    {
        return new StreamParamsConfig([]);
    }

    /**
     * Update stream values
     * 
     * @param mixed $model - if model is null all models set in stream will be recalculated
     * @return bool information if stream was updated
     */
    public function update(?StreamableModel $model): bool
    {
        if ($model) {
            return $this->updateSingle($model);
        }
        return false;
    }

    private function updateSingle(StreamableModel $model): bool
    {
        return false;
    }

    private function updateAll(): bool
    {
        return false;
    }

    /**
     * Access to params
     * @return StreamParams
     */
    final protected function params(): StreamParams
    {
        return $this->params;
    }
}