<?php

namespace Tanzar\Conveyor\Tests\Unit;

use Tanzar\Conveyor\Base\Cells\DataCells;
use Tanzar\Conveyor\Base\Cells\NumberCell;
use Tanzar\Conveyor\Base\Cells\ReactiveCell;
use Tanzar\Conveyor\Base\Conveyor\Conveyor;
use Tanzar\Conveyor\Base\Exceptions\CellLockedException;
use Tanzar\Conveyor\Tests\TestCase;

class ReactiveCellTest extends TestCase
{
    

    public function test_cell_returns_correct_value(): void
    {
        $datacells = new DataCells();
        $datacells->set(new NumberCell(10), 'one');
        $datacells->set(new NumberCell(5), 'two');

        $mock = $this->getMockBuilder(Conveyor::class)
            ->getMock();
        
        $mock->method('cells')
            ->willReturn($datacells);

        $reactive = new ReactiveCell($mock, function(Conveyor $resultSet) {
            $first = $resultSet->cells()->get('one')->getValue();
            $second = $resultSet->cells()->get('two')->getValue();
            return $first + $second;
        });

        $this->assertEquals(15, $reactive->getValue());
    }

    public function test_cell_breaks_loop(): void
    {
        
        $this->assertThrows(function() {
            $mock = $this->getMockBuilder(Conveyor::class)
                ->disableOriginalConstructor()
                ->getMock();

            $one = new ReactiveCell($mock, function(Conveyor $resultSet) {
                return $resultSet->cells()->get('two')->getValue();
            });

            $two = new ReactiveCell($mock, function(Conveyor $resultSet) {
                return $resultSet->cells()->get('one')->getValue();
            });

            $datacells = new DataCells();
            $datacells->set($one, 'one');
            $datacells->set($two, 'two');

            $mock->method('cells')
                ->willReturn($datacells);

            $one->getValue();

            throw new CellLockedException();
        }, CellLockedException::class);
    }
}
