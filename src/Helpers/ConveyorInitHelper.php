<?php

namespace Tanzar\Conveyor\Helpers;

final class ConveyorInitHelper
{

    public function __construct(private string $baseKey)
    {

    }

    /**
     * Attempt to initialize conveyor with given parameters
     * @param array $params
     * @return ConveyorInitHelper
     */
    public function option(array $params): self
    {
        ConveyorUtils::init($this->baseKey, $params);

        return $this;
    }

    /**
     * Initialize all available options for conveyor
     * 
     * @return void
     */
    public function all(): void
    {
        $initializer = ConveyorUtils::makeCore($this->baseKey)
            ->getInitializer();

        foreach ($initializer->toArray() as $option) {
            $this->option($option);
        }
    }
}
