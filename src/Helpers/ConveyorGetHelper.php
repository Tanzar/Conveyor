<?php

namespace Tanzar\Conveyor\Helpers;

use Tanzar\Conveyor\Core\ConveyorCore;
use Tanzar\Conveyor\Exceptions\UnauthorizedAccessException;
use Tanzar\Conveyor\Models\ConveyorFrame;

class ConveyorGetHelper
{
    private ConveyorCore $core;
    private ConveyorFrame $frame;

    public function __construct(string $baseKey, array $params)
    {
        $this->core = ConveyorUtils::makeCore($baseKey);
        $this->frame = ConveyorUtils::findFrame($baseKey, $params);
        
        if (!$this->core->allowAccess($this->frame)) {
            throw new UnauthorizedAccessException($baseKey);
        }
    }

    /**
     * Access current state of conveyor
     * @return array
     */
    public function data(): array
    {
        return $this->core->formatData($this->frame);
    }

    /**
     * Get models used to calculate cell, do not use on reactive cells
     * @param string $model
     * @param string[] $cellKeys
     * @return array
     */
    public function cell(string $model, array $cellKeys): array
    {
        return $this->core->getCellModelsIds($this->frame, $model, $cellKeys);
    }
}
