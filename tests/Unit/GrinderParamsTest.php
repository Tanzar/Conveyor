<?php

namespace Tanzar\Conveyor\Tests\Unit;

use Illuminate\Support\Carbon;
use Tanzar\Conveyor\Exceptions\ConveyorException;
use Tanzar\Conveyor\Grinder\Params\GrinderParams;
use Tanzar\Conveyor\Tests\TestCase;

class GrinderParamsTest extends TestCase
{

    public function test_seters(): void
    {
        $params = new GrinderParams([ 'type', 'price', 'group', 'day', 'month' ]);

        $params->string('type', 'towel')
            ->float('price', 12.35)
            ->int('group', 5)
            ->date('day', Carbon::parse('2025-11-10'))
            ->date('month', Carbon::parse('2025-12-10'), 'Y-m');

        $this->assertDatabaseHas('conveyor_params', [
            'name' => 'type',
            'param_value' => 'towel'
        ]);

        $this->assertDatabaseHas('conveyor_params', [
            'name' => 'price',
            'param_value' => '12.35'
        ]);

        $this->assertDatabaseHas('conveyor_params', [
            'name' => 'group',
            'param_value' => '5'
        ]);

        $this->assertDatabaseHas('conveyor_params', [
            'name' => 'day',
            'param_value' => '2025-11-10'
        ]);

        $this->assertDatabaseHas('conveyor_params', [
            'name' => 'month',
            'param_value' => '2025-12'
        ]);

        $values = $params->getValues();

        $this->assertEquals('towel', $values['type']);
        $this->assertEquals('12.35', $values['price']);
        $this->assertEquals('5', $values['group']);
        $this->assertEquals('2025-11-10', $values['day']);
        $this->assertEquals('2025-12', $values['month']);
    }

    public function test_wrong_key_type(): void
    {
        $this->expectException(ConveyorException::class);

        $params = new GrinderParams([ [ 123 ] ]);
    }

    public function test_key_not_set(): void
    {
        $params = new GrinderParams([]);

        $this->expectException(ConveyorException::class);

        $params->string('type', 'towel');
        $params->float('price', 12.35);
        $params->int('group', 5);
        $params->date('day', Carbon::parse('2025-11-10'));
        $params->date('month', Carbon::parse('2025-12-10'), 'Y-m');
    }

    public function test_copy(): void
    {
        $params = new GrinderParams([ 'type', 'price', 'group', 'day', 'month' ]);

        $params->string('type', 'towel')
            ->float('price', 12.35)
            ->int('group', 5)
            ->date('day', Carbon::parse('2025-11-10'))
            ->date('month', Carbon::parse('2025-12-10'), 'Y-m');

        $copy = $params->copy();
        
        $values = $copy->getValues();

        $this->assertEquals('towel', $values['type']);
        $this->assertEquals('12.35', $values['price']);
        $this->assertEquals('5', $values['group']);
        $this->assertEquals('2025-11-10', $values['day']);
        $this->assertEquals('2025-12', $values['month']);

        $copyModels = $params->getModels();

        $this->assertEquals(
            [ 'id' => 1, 'name' => 'type', 'param_value' => 'towel' ],
            $copyModels['type']->toArray()
        );

        $this->assertEquals(
            [ 'id' => 2, 'name' => 'price', 'param_value' => '12.35' ],
            $copyModels['price']->toArray()
        );

        $this->assertEquals(
            [ 'id' => 3, 'name' => 'group', 'param_value' => '5' ],
            $copyModels['group']->toArray()
        );

        $this->assertEquals(
            [ 'id' => 4, 'name' => 'day', 'param_value' => '2025-11-10' ],
            $copyModels['day']->toArray()
        );

        $this->assertEquals(
            [ 'id' => 5, 'name' => 'month', 'param_value' => '2025-12' ],
            $copyModels['month']->toArray()
        );
    }
}
