<?php

namespace Tanzar\Conveyor\Tests\Unit\Params;

use Tanzar\Conveyor\Exceptions\IncorrectParamOptionsException;
use Tanzar\Conveyor\Params\ParamsInitializer;
use Tanzar\Conveyor\Tests\TestCase;

class ParamsInitializerTest extends TestCase
{

    public function test_add_options(): void
    {
        $config = new ParamsInitializer(
            'testKey',
            [
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

        $config = new ParamsInitializer(
            'testKey',
            [
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
        
        $config = new ParamsInitializer(
            'testKey',
            [
                'user' => 'required|max:0',
            ]);
        
            
        $key = $config->formKey([
            'user' => 1,
            'group' => 3
        ]);

        $this->assertEquals('testKey-user=1;group=3;', $key);
    }

    
    public function test_form_key_with_ignore(): void
    {
        
        $config = new ParamsInitializer(
            'testKey',
            [
                'user' => 'required|max:0',
                'group' => 'required|min:0'
            ],
            [ 'group' ]
        );
        
            
        $key = $config->formKey([
            'user' => 1,
            'group' => 3
        ]);

        $this->assertEquals('testKey-user=1;', $key);
    }
}
