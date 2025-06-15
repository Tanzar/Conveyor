<?php

namespace Tanzar\Conveyor\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property string $base_key
 * @property string $key
 * @property array $params
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Collection<int, ConveyorCell> $cells
 */
final class ConveyorFrame extends Model
{

    protected function casts(): array
    {
        return [
            'params' => 'array',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function cells(): HasMany
    {
        return $this->hasMany(ConveyorCell::class);
    }
}
