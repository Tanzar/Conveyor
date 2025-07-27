<?php

namespace Tanzar\Conveyor\Tests\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tanzar\Conveyor\Models\Traits\UseConveyor;

class FoodWithTrait extends Model
{
    use UseConveyor;
    use SoftDeletes;
    use HasFactory;

    protected static bool $dispatchConveyor = false;

    protected $table = 'testing_foods';
    
    protected static function newFactory()
    {
        return FoodWithTraitFactory::new();
    }
}
