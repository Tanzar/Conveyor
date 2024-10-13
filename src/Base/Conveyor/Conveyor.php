<?php

namespace Tanzar\Conveyor\Base\Conveyor;

use Tanzar\Conveyor\Base\Cells\DataCells;

abstract class Conveyor
{
    private DataCells $cells;

    public function __construct()
    {
        $this->cells = new DataCells();
    }

    final public function run(): array
    {
        $handler = new DataHandler();
        $this->configData($handler);
        $this->init();


        $handler->run();
        $this->after();

        return $this->format();
    }

    abstract protected function configData(DataHandler $data): void;

    abstract protected function init(): void;

    protected function after(): void
    {

    }

    abstract protected function format(): array;

    public function cells(): DataCells
    {
        return $this->cells;
    }
}
