<?php

namespace Tanzar\Conveyor\Tests\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $type
 * @property string $day
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Food extends Model
{
    use HasFactory;

    protected $table = 'testing_foods';

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    
    protected static function newFactory()
    {
        return FoodFactory::new();
    }
}
