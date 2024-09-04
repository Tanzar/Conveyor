<?php

namespace Tanzar\Conveyor\Base\Conveyor;

use Tanzar\Conveyor\Base\Formatter\ResultSet;

abstract class Conveyor
{
    final public function run(): array
    {
        $handler = new DataHandler();
        $this->configData($handler);
        $this->init();


        $handler->run();
        $this->after();

        return $this->result()->format();
    }

    abstract protected function configData(DataHandler $data): void;

    abstract protected function init(): void;

    protected function after(): void
    {

    }

    abstract protected function result(): ResultSet;
}
