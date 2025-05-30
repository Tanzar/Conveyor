<?php

namespace Tanzar\Conveyor\Tests\Unit\Stream;

use Tanzar\Conveyor\Exceptions\IncorrectParamOptionsException;
use Tanzar\Conveyor\Params\StreamParamsConfig;
use Tanzar\Conveyor\Tests\TestCase;

class ParamsConfigTest extends TestCase
{

    public function test_add_options(): void
    {
        $config = new StreamParamsConfig([
            'user' => 'required|integer',
            'group' => 'required|integer'
        ]);
        
        $config->option([
            'user' => 1,
            'group' => 3
        ]);

        $config->option([
            'user' => 2,
            'group' => 1
        ]);

        $expected = [
            [ 'user' => 1, 'group' => 3 ],
            [ 'user' => 2, 'group' => 1 ]
        ];

        $this->assertEquals($expected, $config->toArray());
    }

    public function test_exception(): void
    {
        $this->expectException(IncorrectParamOptionsException::class);

        $config = new StreamParamsConfig([
            'user' => 'required|max:0',
        ]);
        
        $config->option([
            'user' => 1,
            'group' => 3
        ]);

        $config->option([ 'user' => 'Admin' ], false);

        $this->assertEquals([], $config->toArray());
    }

    public function test_form_key(): void
    {
        $key = StreamParamsConfig::formKey([
            'user' => 1,
            'group' => 3
        ]);

        $this->assertEquals('user=1;group=3;', $key);
    }
}
