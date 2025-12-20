<?php

namespace Tanzar\Conveyor\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $projector_key
 * @property string $projector_params_key
 * @property array $current_state
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class ConveyorActiveProjector extends Model
{
    protected $casts = [
        'current_state' => 'array',
    ];
}
