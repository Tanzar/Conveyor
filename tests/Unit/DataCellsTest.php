<?php

namespace Tanzar\Conveyor\Tests\Unit;
use Tanzar\Conveyor\Base\Cells\DataCells;
use Tanzar\Conveyor\Base\Cells\NumberCell;
use Tanzar\Conveyor\Tests\TestCase;

class DataCellsTest extends TestCase
{

    public function test_retrieve_correct_cell(): void
    {
        $cells = new DataCells();

        $cells->set(new NumberCell(1), 'first');
        $cells->set(new NumberCell(2), 'second');
        $cells->set(new NumberCell(3), 'third');
        
        $first = $cells->get('first');
        $second = $cells->get('second');
        $third = $cells->get('third');

        $this->assertNotNull($first);
        $this->assertNotNull($second);
        $this->assertNotNull($third);
        $this->assertEquals(1, $first->getValue());
        $this->assertEquals(2, $second->getValue());
        $this->assertEquals(3, $third->getValue());
    }

    public function test_multiple_keys(): void
    {
        $cells = new DataCells();

        $cells->set(new NumberCell(1), 'first', 'first');
        $cells->set(new NumberCell(2), 'first', 'second');
        $cells->set(new NumberCell(3), 'second');
        
        
        $first = $cells->get('first', 'first');
        $second = $cells->get('first', 'second');
        $third = $cells->get('second');

        $this->assertNotNull($first);
        $this->assertNotNull($second);
        $this->assertNotNull($third);
        $this->assertEquals(1, $first->getValue());
        $this->assertEquals(2, $second->getValue());
        $this->assertEquals(3, $third->getValue());
    }

    public function test_retrieving_non_existent_cell(): void
    {
        $cells = new DataCells();

        $cell = $cells->get('first');

        $this->assertNull($cell);
    }
}
