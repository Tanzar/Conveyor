<?php

namespace Tanzar\Conveyor\Configs;

interface ModelConfigInterface
{

    public function handler(callable $handler): self;

    public function query(callable $build): self;

    public function idColumn(string $column): self;
}
