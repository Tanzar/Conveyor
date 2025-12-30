<?php

namespace Tanzar\Conveyor\Grinder;

use Tanzar\Conveyor\Exceptions\ConveyorException;

final class GrinderSetup
{
    private array $calculators = [];

    public function add(string $key, callable $calculation): void
    {
        $this->calculators[$key] = $calculation;
    }

    /**
     * 
     * @return string[]
     */
    public function keys(): array
    {
        return array_keys($this->calculators);
    }

    public function get(string $key): callable
    {
        if (!isset($this->calculators[$key])) {
            throw new ConveyorException("Key: $key is not set in grinder setup");
        }

        return $this->calculators[$key];
    }
}
