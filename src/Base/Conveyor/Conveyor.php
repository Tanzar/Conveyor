<?php

namespace Tanzar\Conveyor\Base\Conveyor;

use Tanzar\Conveyor\Base\Cells\DataCells;

abstract class Conveyor
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
        $this->configData($this->handler);


        $this->handler->run();
        $this->after();

        return $this->format();
    }

    abstract protected function configData(DataHandler $data): void;

    abstract protected function init(): void;

    protected function after(): void
    {

    }

    abstract protected function format(): array;

    final protected function cells(): DataCells
    {
        return $this->cells;
    }
}
