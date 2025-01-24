<?php

namespace Tanzar\Conveyor\Base\Handler;

use Illuminate\Support\Collection;
use Tanzar\Conveyor\Base\Feeder\Feeder;

final class DataHandler
{
    private Collection $feeders;
    private Collection $handles;
    private int $index = 0;

    public function __construct()
    {
        $this->feeders = new Collection();
        $this->handles = new Collection();
    }

    public function add(Feeder $feeder, callable $handle): void
    {
        $this->feeders->put($this->index, $feeder);
        $this->handles->put($this->index, $handle);
        $this->index++;
    }

    public function run(): void
    {
        /** @var Feeder $feeder */
        foreach ($this->feeders as $index => $feeder) {
            if ($this->handles->has($index)) {
                $feeder->each($this->handles->get($index));
            }
        }
    }

    public function reset(): void
    {
        $this->feeders = new Collection();
        $this->handles = new Collection();
        $this->index = 0;
    }

}
