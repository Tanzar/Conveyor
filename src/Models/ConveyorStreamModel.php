<?php

namespace Tanzar\Conveyor\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $conveyor_stream_id
 * @property int $streamable_id
 * @property string $streamable_type
 * @property array $current_state
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class ConveyorStreamModel extends Model
{
    
    protected function casts(): array
    {
        return [
            'current_state' => 'array',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function streamable(): MorphTo
    {
        return $this->morphTo();
    }
}
