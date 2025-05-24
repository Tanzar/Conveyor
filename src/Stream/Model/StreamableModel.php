<?php


namespace Tanzar\Conveyor\Stream\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Tanzar\Conveyor\Models\ConveyorStreamModel;

abstract class StreamableModel extends Model
{
	
    final public function conveyors(): MorphMany
    {
        return $this->morphMany(ConveyorStreamModel::class, 'streamable');
    }
}