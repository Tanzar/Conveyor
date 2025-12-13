<?php

namespace Tanzar\Conveyor\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $calculable_id
 * @property int $calculable_key_id
 * @property int $cell_id
 * @property float $cell_value
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class ConveyorModelValue extends Model
{

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function cell(): BelongsTo
    {
        return $this->belongsTo(ConveyorCellValue::class);
    }

    public function calculableKey(): BelongsTo
    {
        return $this->belongsTo(ConveyorCalculableKey::class);
    }
}
