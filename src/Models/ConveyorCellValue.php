<?php

namespace Tanzar\Conveyor\Models;

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
        return $this->belongsTo(ConveyorCellKey::class);
    }

    public function grinder(): BelongsTo
    {
        return $this->belongsTo(ConveyorGrinderKey::class);
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
