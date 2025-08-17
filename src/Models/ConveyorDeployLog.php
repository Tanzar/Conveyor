<?php

namespace Tanzar\Conveyor\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $class_name
 * @property Carbon $modified_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class ConveyorDeployLog extends Model
{

    protected $casts = [
        'modified_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
