<?php

namespace Tanzar\Conveyor\Tests\Unit\Configs;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Tanzar\Conveyor\Configs\ModelConfig;
use Tanzar\Conveyor\Exceptions\InvalidBuildFunctionException;
use Tanzar\Conveyor\Exceptions\InvalidHandlerException;
use Tanzar\Conveyor\Exceptions\InvalidClassException;
use Tanzar\Conveyor\Params\Params;
use Tanzar\Conveyor\Tests\Models\Tester;
use Tanzar\Conveyor\Tests\TestCase;

class ModelConfigTest extends TestCase
{
    public function test_invalid_class(): void
    {
        $this->expectException(InvalidClassException::class);

        $config = new ModelConfig(Model::class);
    }

    public function test_handlers(): void
    {
        $model = new Tester();

        $config = new ModelConfig($model::class);
        
        $val = 0;

        $config->handler(function(Tester $tester) use (&$val) {
                $val += 10;
            })
            ->handler(function(Tester $tester) use (&$val) {
                $val += 10;
            });

        $handlers = $config->getHandlers();

        $this->assertCount(2, $handlers);

        foreach ($handlers as $handler) {
            $handler($model);
        }

        $this->assertEquals(20, $val);
    }

    public function test_handlers_exceptions(): void
    {
        $config = new ModelConfig(Tester::class);
        
        $this->expectException(InvalidHandlerException::class);

        $config->handler(function () {});

        
        $this->expectException(InvalidHandlerException::class);

        $config->handler(function (int $first, string $second) {});

    }

    public function test_query(): void
    {
        Tester::factory()->count(5)->create();

        $config = new ModelConfig(Tester::class);
        
        $config->query(function(Builder $query, Params $params) {
            $query->whereNotNull('created_at');
        });


        $sql = $config->getQuery(new Params([]));

        $items = $sql->get();

        $this->assertEquals(5, $items->count());
    }

    public function test_query_exceptions(): void
    {
        $config = new ModelConfig(Tester::class);
        
        $this->expectException(InvalidBuildFunctionException::class);

        $config->query(function() { });

        $this->expectException(InvalidBuildFunctionException::class);

        $config->query(function(Params $params) { });

        $this->expectException(InvalidBuildFunctionException::class);

        $config->query(function(Builder $query, Tester $test) { });
    }

    public function test_id_column(): void
    {
        $config = new ModelConfig(Tester::class);
        
        $config->idColumn('model_id');

        $this->assertEquals('model_id', $config->getIdColumn());
    }
}
