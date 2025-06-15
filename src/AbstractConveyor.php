<?php

namespace Tanzar\Conveyor;

use Tanzar\Conveyor\Base\Handler\DataHandler;
use Tanzar\Conveyor\Cells\Cells;

abstract class AbstractConveyor
{
    private Cells $cells;
    private DataHandler $handler;

    public function __construct()
    {
        $this->cells = new Cells();
        $this->handler = new DataHandler();
    }

    final public function run(): array
    {
        $this->setup();
        $this->reset();
        $this->cells->reset();
        $this->handler->reset();
        $this->initHandler($this->handler);


        $this->handler->run();
        $this->postProcessing();

        return $this->format();
    }

    abstract protected function setup(): void;

    protected function reset(): void
    {

    }

    abstract protected function initHandler(DataHandler $handler): void;

    protected function postProcessing(): void
    {

    }

    abstract protected function format(): array;

    final protected function cells(): Cells
    {
        return $this->cells;
    }
}
