<?php

namespace Tanzar\Conveyor\Tests\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Tanzar\Conveyor\Models\ConveyorModel;

class Tester extends ConveyorModel
{
    use HasFactory;

    protected static function newFactory()
    {
        return TesterFactory::new();
    }
}
