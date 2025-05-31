<?php


namespace Tanzar\Conveyor\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

abstract class ConveyorCacheableModel extends Model
{
	
    final public function cachedModelConveyors(): MorphMany
    {
        return $this->morphMany(ConveyorsModelsCache::class, 'model');
    }
}