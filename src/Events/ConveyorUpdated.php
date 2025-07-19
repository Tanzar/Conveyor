<?php

namespace Tanzar\Conveyor\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Tanzar\Conveyor\Helpers\ConveyorUtils;
use Tanzar\Conveyor\Models\ConveyorFrame;

class ConveyorUpdated implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public array $data;

    public function __construct(public ConveyorFrame $frame)
    {
        $core = ConveyorUtils::makeCore($frame->base_key);

        $this->data = $core->formatData($frame);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('conveyor.' . $this->frame->key),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'conveyor.updated';
    }
}
