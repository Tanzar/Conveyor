<?php


namespace Tanzar\Conveyor\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

abstract class StreamableModel extends Model
{
	
    final public function conveyors(): MorphMany
    {
        return $this->morphMany(ConveyorStreamModel::class, 'streamable');
    }
}