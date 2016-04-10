<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

/**  Timer Event
 *
 * This application will have a server-sided timer set to `x`-amount of seconds.
 * When this timer expires, this event is to be fired off. The CheckForIncidents
 * Listener will receive this and poll through a traffic API for each client.
 *
 * This Event does not require any parameters.
 */

class TimerExpired extends Event
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
