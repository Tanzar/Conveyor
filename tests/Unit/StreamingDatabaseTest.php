<?php

namespace Tanzar\Conveyor\Tests\Unit;

use Tanzar\Conveyor\Models\ConveyorsModelsCache;
use Tanzar\Conveyor\Models\ConveyorsCache;
use Tanzar\Conveyor\Tests\Models\Tester;
use Tanzar\Conveyor\Tests\TestCase;

class StreamingDatabaseTest extends TestCase
{
    
    public function test_conveyor_streams(): void
    {
        $conveyor = new ConveyorsCache();

        $conveyor->base_key = 'key';
        $conveyor->key = 'key_one';
        $conveyor->params = [];
        $conveyor->current_state = [
            'headers' => [],
            'rows' => [],
            'cells' => [],
        ];

        $conveyor->save();

        $this->assertDatabaseCount('conveyors_cache', 1);

        $testerModel = new Tester();
        $testerModel->save();
        
        $streamModel = new ConveyorsModelsCache();
        $streamModel->conveyor_id = $conveyor->id;
        $streamModel->current_state = [
            'cells' => []
        ];

        $testerModel->cachedModelConveyors()
            ->save($streamModel);

        $this->assertDatabaseCount('conveyors_models_cache', 1);
        $this->assertDatabaseHas('conveyors_models_cache', [
            'model_type' => Tester::class
        ]);
    }

}
