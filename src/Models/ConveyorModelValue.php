<?php

namespace Tanzar\Conveyor\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $calculable_id
 * @property string $calculable_type
 * @property int $conveyor_extractor_key_id
 * @property int $conveyor_cell_key_id
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

    public function cellKey(): BelongsTo
    {
        return $this->belongsTo(ConveyorCellKey::class);
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(ConveyorVariantKey::class);
    }

    public function extractor(): BelongsTo
    {
        return $this->belongsTo(ConveyorExtractorKey::class);
    }
}
