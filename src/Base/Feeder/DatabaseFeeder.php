<?php

namespace Tanzar\Conveyor\Base\Feeder;

use Illuminate\Database\Eloquent\Builder as Eloquent;
use \Illuminate\Database\Query\Builder as DB;

final class DatabaseFeeder implements Feeder
{
    public function __construct(
        private Eloquent|DB $query,
        private string $idColumn = 'id',
        private int $chunk = 1000
    ) {

    }

    public function each(callable $callable): void
    {
        $chunk = ($this->chunk > 0) ? $this->chunk : 1000;
        $this->query->lazyById($chunk, $this->idColumn)
            ->each($callable);
    }

}
