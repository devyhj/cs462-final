<?php

namespace App\Listeners;

use App\Events\IncidentFound;
use DB;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Twilio;
use App\Incident;
use App\User;

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
        $incidents = $event->getIncidents();
        $users = User::all();
        foreach($incidents as $incident)
        {
            $message = '';

            $message = 'Location: '.$incident->location.' / Description: '.$incident->description;
            foreach($users as $user){
                // If you're getting SSL cert errors, add `CURLOPT_SSL_VERIFYPEER => FALSE,` after line 59 in --> vendor/twilio/sdk/services/twilio/TinyHttp.php
                Twilio::message($user->phone_number, $message);
            }
            
        }
        // Twilio::message($event->user->phone, $event->message);
    }
}
