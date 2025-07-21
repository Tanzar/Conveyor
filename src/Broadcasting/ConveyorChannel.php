<?php

namespace Tanzar\Conveyor\Broadcasting;

use Tanzar\Conveyor\Helpers\ConveyorUtils;
use Tanzar\Conveyor\Models\ConveyorFrame;

class ConveyorChannel
{
    /**
     * Create a new channel instance.
     */
    public function __construct() {}
 
    /**
     * Authenticate the user's access to the channel.
     */
    public function join(string $user, string $key): array|bool
    {
        $frame = ConveyorFrame::query()
            ->where('key', $key)
            ->first();

        if ($frame === null) {
            return false;
        }

        $core = ConveyorUtils::makeCore($frame->base_key);
            
        if ($core->allowAccess($frame)) {
            return $core->formatData($frame);
        }
        return false;
    }
}
