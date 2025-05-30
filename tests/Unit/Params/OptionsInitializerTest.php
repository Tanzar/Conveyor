<?php

namespace Tanzar\Conveyor\Tests\Unit\Params;

use Tanzar\Conveyor\Exceptions\IncorrectParamOptionsException;
use Tanzar\Conveyor\Params\OptionsInitializer;
use Tanzar\Conveyor\Tests\TestCase;

class OptionsInitializerTest extends TestCase
{

    public function test_add_options(): void
    {
        $config = new OptionsInitializer([
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

        $config = new OptionsInitializer([
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
        $key = OptionsInitializer::formKey([
            'user' => 1,
            'group' => 3
        ]);

        $this->assertEquals('user=1;group=3;', $key);
    }
}
