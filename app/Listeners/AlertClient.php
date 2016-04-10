<?php

namespace App\Listeners;

use App\Events\IncidentFound;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AlertClient
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  IncidentFound  $event
     * @return void
     */
    public function handle(IncidentFound $event)
    {
        //
    }
}