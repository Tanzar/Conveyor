<?php

namespace Tanzar\Conveyor\Tests\Unit;

use Tanzar\Conveyor\Base\Cells\NumberCell;
use Tanzar\Conveyor\Base\Cells\ReactiveCell;
use Tanzar\Conveyor\Base\Conveyor\AbstractConveyor;
use Tanzar\Conveyor\Base\Exceptions\CellLockedException;
use Tanzar\Conveyor\Tests\TestCase;

class ReactiveCellTest extends TestCase
{
    

    public function test_cell_returns_correct_value(): void
    {
        $one = new NumberCell(10);
        $two = new NumberCell(5);

        $reactive = new ReactiveCell(function() use ($one, $two) {
            return $one->getValue() + $two->getValue();
        });

        $this->assertEquals(15, $reactive->getValue());
    }

    public function test_cell_breaks_loop(): void
    {
        
        $this->assertThrows(function() {

            $two = new NumberCell(10);

            $one = new ReactiveCell(function() use (&$two)  {
                return $two->getValue();
            });

            $two = new ReactiveCell(function() use ($one) {
                return $one->getValue();
            });

            $one->getValue();

            throw new CellLockedException();
        }, CellLockedException::class);
    }
}
