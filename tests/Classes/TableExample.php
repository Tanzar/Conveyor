<?php

namespace Tanzar\Conveyor\Tests\Classes;

use Tanzar\Conveyor\Base\Feeder\ArrayFeeder;
use Tanzar\Conveyor\Cells\NumberCell;
use Tanzar\Conveyor\Cells\ReactiveCell;
use Tanzar\Conveyor\Table\Frame\Rows;
use Tanzar\Conveyor\Table\Frame\Columns;
use Tanzar\Conveyor\Cells\DataCell;
use Tanzar\Conveyor\Base\Handler\DataHandler;
use Tanzar\Conveyor\Table\Table;

class TableExample extends Table
{

    public function setupRows(Rows $rows): void
    {
        $rows->add('total', 'Total');
        $rows->add('pizzas', 'Pizzas');
        $rows->add('burgers', 'Burgers');
        $rows->add('drinks', 'Drinks')->hide();
    }

    public function setupColumns(Columns $columns): void
    {
        $columns->add('today', 'First');
        $columns->add('yesterday', 'Second');
    }

    protected function defaultCell(string $row, string $column): DataCell
    {
        if ($row === 'total') {
            return new ReactiveCell(function () use ($column) {
                return $this->get('pizzas', $column)->getValue() + 
                    $this->get('burgers', $column)->getValue();
            });
        }
        return new NumberCell();
    }

    protected function initHandler(DataHandler $handler): void
    {
        $feeder = new ArrayFeeder([
            [ 'type' => 'pizza', 'day' => '2012-01-03'],
            [ 'type' => 'pizza', 'day' => '2012-01-03'],
            [ 'type' => 'pizza', 'day' => '2012-01-04'],
            [ 'type' => 'pizza', 'day' => '2012-01-04'],
            [ 'type' => 'pizza', 'day' => '2012-01-04'],
            [ 'type' => 'burger', 'day' => '2012-01-03'],
            [ 'type' => 'burger', 'day' => '2012-01-04'],
            [ 'type' => 'burger', 'day' => '2012-01-04'],
            [ 'type' => 'burger', 'day' => '2012-01-04'],
            [ 'type' => 'burger', 'day' => '2012-01-04'],
            [ 'type' => 'burger', 'day' => '2012-01-04'],
            [ 'type' => 'cola', 'day' => '2012-01-03'],
            [ 'type' => 'cola', 'day' => '2012-01-03'],
            [ 'type' => 'cola', 'day' => '2012-01-03'],
            [ 'type' => 'cola', 'day' => '2012-01-03'],
            [ 'type' => 'cola', 'day' => '2012-01-03'],
        ]);
        $handler->add($feeder, function(array $item) {
            $this->countBurger($item);
            $this->countPizza($item);
            $this->countDrink($item);
        });
    }

    private function countBurger(array $item): void
    {
        if ($item['type'] === 'burger') {
            $cell = $this->get(
                'burgers', 
                ($item['day'] === '2012-01-04') ? 'today' : 'yesterday'
            ); 

            /** @var NumberCell $cell */
            $cell->change(1);
        }
    }

    private function countPizza(array $item): void
    {
        if ($item['type'] === 'pizza') {
            $cell = $this->get(
                'pizzas', 
                ($item['day'] === '2012-01-04') ? 'today' : 'yesterday'
            ); 

            /** @var NumberCell $cell */
            $cell->change(1);
        }
    }

    private function countDrink(array $item): void
    {
        if ($item['type'] === 'drink') {
            $cell = $this->get(
                'drinks', 
                ($item['day'] === '2012-01-04') ? 'today' : 'yesterday'
            ); 

            /** @var NumberCell $cell */
            $cell->change(1);
        }
    }

}
