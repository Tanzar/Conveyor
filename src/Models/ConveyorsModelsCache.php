<?php

namespace Tanzar\Conveyor\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $conveyor_id
 * @property int $model_id
 * @property string $model_type
 * @property array $current_state
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class ConveyorsModelsCache extends Model
{
    protected string $table = 'conveyors_models_cache';
    
    protected function casts(): array
    {
        return [
            'current_state' => 'array',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function cacheable(): MorphTo
    {
        return $this->morphTo();
    }
}
