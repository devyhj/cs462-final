<?php

namespace App\Listeners;

use App\Events\TimerExpired;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CheckForIncidents implements ShouldQueue
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
     * @param  TimerExpired  $event
     * @return void
     */
    public function handle(TimerExpired $event)
    {
        //
    }
}
