<?php

namespace Tanzar\Conveyor\Base\Feeder;

interface Feeder
{
    public function each(callable $callable): void;
}
