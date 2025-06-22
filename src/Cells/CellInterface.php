<?php

namespace Tanzar\Conveyor\Cells;

interface CellInterface
{
    public function hidden(?bool $hide = null): bool;

    public function setReactive(?callable $handle): void;

    public function change(float $value): void;

    public function getValue(): float;

    public function setOption(string $key, bool|string|float $value): void;

    public function getOptionAsBool(string $key): bool;
    
    public function getOptionAsString(string $key): string;
    
    public function getOptionAsFloat(string $key): float;
}
