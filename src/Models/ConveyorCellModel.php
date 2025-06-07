<?php

namespace Tanzar\Conveyor\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $conveyor_cell_id
 * @property int $model_id
 * @property string $model_type
 * @property float $value
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class ConveyorCellModel extends Model
{
    
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function model(): MorphTo
    {
        return $this->morphTo();
    }
}
