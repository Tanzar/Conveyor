<?php

namespace Tanzar\Conveyor\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $grinder_key_id
 * @property int $cell_key_id
 * @property float $cell_value
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read ConveyorCellKey $cellKey
 * @property-read ConveyorGrinderKey $grinder
 * @property-read Collection<int, ConveyorParam> $params
 * 
 */
class ConveyorCellValue extends Model
{

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function cellKey(): BelongsTo
    {
        return $this->belongsTo(ConveyorCellKey::class, 'cell_key_id');
    }

    public function grinder(): BelongsTo
    {
        return $this->belongsTo(ConveyorGrinderKey::class, 'grinder_key_id');
    }

    public function params(): BelongsToMany
    {
        return $this->belongsToMany(
            ConveyorParam::class,
            'conveyor_cell_params',
            'cell_id',
            'param_id'
        );
    }
}
