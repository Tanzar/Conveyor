<?php

namespace Tanzar\Conveyor\Tests\Unit;

use Tanzar\Conveyor\Base\Cells\NumberCell;
use Tanzar\Conveyor\Base\Cells\ReactiveCell;
use Tanzar\Conveyor\Base\Exceptions\CellLockedException;
use Tanzar\Conveyor\Base\Formatter\ResultSet;
use Tanzar\Conveyor\Tests\TestCase;

class ReactiveCellTest extends TestCase
{
    

    public function test_cell_returns_correct_value(): void
    {
        $mock = $this->getMockBuilder(ResultSet::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mock->method('get')
            ->willReturn(new NumberCell(10), new NumberCell(5));

        $reactive = new ReactiveCell($mock, function($resultSet) {
            return $resultSet->get()->getValue() + $resultSet->get()->getValue();
        });

        $this->assertEquals(15, $reactive->getValue());
    }

    public function test_cell_breaks_loop(): void
    {
        
        $this->assertThrows(function() {
            $mock = $this->getMockBuilder(ResultSet::class)
            ->disableOriginalConstructor()
            ->getMock();


            $reactive = new ReactiveCell($mock, function($resultSet) {
                return $resultSet->get()->getValue() + $resultSet->get()->getValue();
            });

            $second = new ReactiveCell($mock, function($resultSet) {
                return $resultSet->get()->getValue();
            });

            $mock->method('get')
                ->willReturn(
                    new NumberCell(10), 
                    $second,
                    $reactive
                );

            $reactive->getValue();
            }, CellLockedException::class);
    }
}
