<?php

namespace Tanzar\Conveyor\Tests\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tester extends Model
{
    use HasFactory;

    protected static function newFactory()
    {
        return TesterFactory::new();
    }
}
