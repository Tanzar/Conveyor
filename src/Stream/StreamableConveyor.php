<?php


namespace Tanzar\Conveyor\Stream;

use Tanzar\Conveyor\Models\ConveyorStream;
use Tanzar\Conveyor\Stream\Model\StreamableModel;
use Tanzar\Conveyor\Stream\Params\StreamParams;
use Tanzar\Conveyor\Stream\Params\StreamParamsConfig;

abstract class StreamableConveyor
{
    private ConveyorStream $stream;
    private StreamConfig $config;
    private StreamParams $params;
    
    public function __construct(ConveyorStream $stream)
    {
        $this->stream = $stream;
        $this->params = new StreamParams($stream->params);

        $this->config = new StreamConfig();
        $this->setup($this->config);
    }

    abstract protected function setup(StreamConfig $config): void;

    /**
     * Setup for params
     * Options set in resulting object will be used to generate streams with command
     * @return StreamParamsConfig
     */
    public static function paramsConfig(): StreamParamsConfig
    {
        return new StreamParamsConfig([]);
    }

    /**
     * Update stream values
     * 
     * @param StreamableModel $model - if model is null all models set in stream will be recalculated
     * @return bool information if stream was updated
     */
    public function update(?StreamableModel $model): bool
    {
        if ($model) {
            return $this->updateSingle($model);
        }
        return $this->updateAll();
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