<?php

namespace Tanzar\Conveyor\Helpers;

use Tanzar\Conveyor\Core\ConveyorCore;
use Tanzar\Conveyor\Models\ConveyorFrame;

final class ConveyorInitHelper
{
    private ConveyorCore $core;

    public function __construct(private string $baseKey)
    {
        $this->core = ConveyorUtils::makeCore($baseKey);
    }

    /**
     * Attempt to initialize conveyor with given parameters
     * @param array $params
     * @return ConveyorInitHelper
     */
    public function option(array $params): self
    {
        $initializer = $this->core->getInitializer();

        $valid = $initializer->checkValidity($params);

        $key = $this->baseKey . '-' . $initializer->formKey($valid);

        $doesntExist = ConveyorFrame::query()
            ->where('key', $key)
            ->doesntExist();

        if ($doesntExist) {
            $frame = new ConveyorFrame();
            $frame->key = $key;
            $frame->base_key = $this->baseKey;
            $frame->params = $valid;
            $frame->save();
        }

        return $this;
    }

    /**
     * Initialize all available options for conveyor
     * 
     * @return void
     */
    public function all(): void
    {
        $initializer = $this->core->getInitializer();

        foreach ($initializer->toArray() as $option) {
            $this->option($option);
        }
    }
}
