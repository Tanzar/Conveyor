<?php

namespace Tanzar\Conveyor\Base\Feeder;

use Illuminate\Support\Collection;

final class ArrayFeeder implements Feeder
{
    public function __construct(
        private array|Collection $items
    ) {

    }

    public function each(callable $callable): void
    {
        foreach ($this->items as $key => $item) {
            $callable($item, $key);
        }
    }

}
