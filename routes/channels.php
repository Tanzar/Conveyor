<?php

use Illuminate\Support\Facades\Broadcast;
use Tanzar\Conveyor\Broadcasting\ConveyorChannel;

Broadcast::channel('conveyor.{key}', ConveyorChannel::class);