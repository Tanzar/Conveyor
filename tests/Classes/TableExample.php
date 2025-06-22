<?php

namespace Tanzar\Conveyor\Tests\Classes;

use Tanzar\Conveyor\Configs\ConveyorConfigInterface;
use Tanzar\Conveyor\Table\Frame\Rows;
use Tanzar\Conveyor\Table\Frame\Columns;
use Tanzar\Conveyor\Table\Table;
use Tanzar\Conveyor\Tests\Models\Food;

class TableExample extends Table
{
    public function setupRows(Rows $rows): void 
    {
        $rows->add('burger', 'Burgers');
        $rows->add('pizza', 'Pizzas');
    }

    public function setupColumns(Columns $columns): void
    {
        $columns->add('total', 'Sum');
        $columns->add('today', 'Today');
        $columns->add('yesterday', 'Yesterday');
    }

    protected function setup(ConveyorConfigInterface $config): void
    {
        $config->model(Food::class)
            ->handler(function (Food $food) {
                $this->get($food->type, $food->day)
                    ->change(1);
            });
    }

    protected function postProcessing(): void
    {
        $this->get('burger', 'total')
            ->setReactive(function() {
                return $this->get('burger', 'today')->getValue() +
                    $this->get('burger', 'yesterday')->getValue();
            });

        $this->get('pizza', 'total')
            ->setReactive(function() {
                return $this->get('pizza', 'today')->getValue() +
                    $this->get('pizza', 'yesterday')->getValue();
            });
    }

}
