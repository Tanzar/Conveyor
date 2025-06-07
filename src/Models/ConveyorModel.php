<?php


namespace Tanzar\Conveyor\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

abstract class ConveyorModel extends Model
{
	
    final public function conveyorsCells(): MorphMany
    {
        return $this->morphMany(ConveyorCellModel::class, 'model');
    }
}