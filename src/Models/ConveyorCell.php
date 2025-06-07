<?php

namespace Tanzar\Conveyor\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $conveyor_frame_id
 * @property string $key
 * @property bool $hidden
 * @property float $value
 * @property array $options
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class ConveyorCell extends Model
{

    protected function casts(): array
    {
        return [
            'hidden' => 'boolean',
            'options' => 'array',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function conveyor(): BelongsTo
    {
        return $this->belongsTo(ConveyorFrame::class);
    }

    public function models(): HasMany
    {
        return $this->hasMany(ConveyorCellModel::class);
    }
}
