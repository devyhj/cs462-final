<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class IncidentFound extends Event
{
    use SerializesModels;

    public $incidents = array();

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($incidents)//User data as parameter?
    {
        $this->incidents = $incidents;
    }

    public function getIncidents()
    {
        return $this->incidents;
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
