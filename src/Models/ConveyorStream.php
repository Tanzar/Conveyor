<?php

namespace Tanzar\Conveyor\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $conveyor_class
 * @property string $key
 * @property array $current_state
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class ConveyorStream extends Model
{

    protected function casts(): array
    {
        return [
            'current_state' => 'array',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}
