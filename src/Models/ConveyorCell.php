<?php

namespace Tanzar\Conveyor\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $conveyor_extractor_key_id
 * @property int $conveyor_tag_key_id
 * @property int $conveyor_cell_key_id
 * @property float $cell_value
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class ConveyorCell extends Model
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

    public function tag(): BelongsTo
    {
        return $this->belongsTo(ConveyorTagKey::class);
    }

    public function extractor(): BelongsTo
    {
        return $this->belongsTo(ConveyorExtractorKey::class);
    }

    public function params(): BelongsToMany
    {
        return $this->belongsToMany(ConveyorParam::class, 'conveyor_cell_params');
    }
}
