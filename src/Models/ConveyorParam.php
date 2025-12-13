<?php

namespace Tanzar\Conveyor\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property string $name
 * @property string $param_value
 */
class ConveyorParam extends Model
{
    public $timestamps = false;

    public function cells(): BelongsToMany
    {
        return $this->belongsToMany(
            ConveyorCellValue::class,
            'conveyor_cell_params',
            'param_id',
            'cell_id'
        );
    }
}
