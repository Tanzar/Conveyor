<?php

namespace Tanzar\Conveyor\Tests\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Tanzar\Conveyor\Models\ConveyorCacheableModel;

class Tester extends ConveyorCacheableModel
{
    use HasFactory;

    protected static function newFactory()
    {
        return TesterFactory::new();
    }
}
