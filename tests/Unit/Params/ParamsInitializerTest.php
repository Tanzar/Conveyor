<?php

namespace Tanzar\Conveyor\Tests\Unit\Params;

use Tanzar\Conveyor\Exceptions\IncorrectParamOptionsException;
use Tanzar\Conveyor\Params\ParamsInitializer;
use Tanzar\Conveyor\Tests\TestCase;

class ParamsInitializerTest extends TestCase
{

    public function test_add_options(): void
    {
        $config = new ParamsInitializer([
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

        $config = new ParamsInitializer([
            'user' => 'required|max:0',
        ]);
        
        $config->option([
            'user' => 1,
            'group' => 3
        ]);

        $config->option([ 'user' => 'Admin' ]);

        $this->assertEquals([], $config->toArray());
    }

    public function test_form_key(): void
    {
        $initializer = new ParamsInitializer();

        $key = $initializer->formKey([
            'user' => 1,
            'group' => 3
        ]);

        $this->assertEquals('user=1;group=3;', $key);
    }
}
