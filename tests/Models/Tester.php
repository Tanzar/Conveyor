<?php

namespace Tanzar\Conveyor\Tests\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Tanzar\Conveyor\Stream\Model\StreamableModel;

class Tester extends StreamableModel
{
    use HasFactory;

    protected static function newFactory()
    {
        return TesterFactory::new();
    }
}
