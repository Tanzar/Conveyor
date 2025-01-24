<?php

namespace Tanzar\Conveyor\Base;

use Tanzar\Conveyor\Base\Cells\DataCells;
use Tanzar\Conveyor\Base\Handler\DataHandler;

abstract class AbstractConveyor
{
    private DataCells $cells;
    private DataHandler $handler;

    public function __construct()
    {
        $this->cells = new DataCells();
        $this->handler = new DataHandler();
    }

    final public function run(): array
    {
        $this->init();
        $this->cells->reset();
        $this->handler->reset();
        $this->initHandler($this->handler);


        $this->handler->run();
        $this->postProcessing();

        return $this->format();
    }

    abstract protected function initHandler(DataHandler $handler): void;

    abstract protected function init(): void;

    protected function postProcessing(): void
    {

    }

    abstract protected function format(): array;

    final protected function cells(): DataCells
    {
        return $this->cells;
    }
}
