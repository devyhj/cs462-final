<?php

namespace App\Listeners;

use App\Events\IncidentFound;
use DB;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Twilio;
use App\Incident;

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
        foreach($incidents as $incident)
        {
            // dd($incident);
            $points = $incident->point->coordinates;
            $googleKey = Config::get('geocoder.bing.key');
            $url = 'https://maps.googleapis.com/maps/api/geocode/json?latlng='.$points[0].','.$points[1]."&key=".$googleKey;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url); 
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            //curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            $result = json_decode(curl_exec($ch));
            curl_close($ch);
            // dd($result);
            $external_id = $incident->incidentId;
            $description = $incident->description;
            $address = $result->results[0]->formatted_address;
            $count = Incident::where('external_id', $external_id)->count();
            
            /* Test Block
            $external_id = 123456;
            $description = 'WINDOWS IS TERRIBLE';
            $address = '224 Meh Way, Somewhere Nowhere';
            $count = Incident::where('external_id', $external_id)->count();
            */
            if($count == 0) //if new incident
            {
                $newIncident = new Incident;
                $newIncident->location = $address;
                $newIncident->description = $description;
                $newIncident->external_id=$external_id;
                $newIncident->save();
                $users = DB::select('select * from users');
                foreach($users as $user){
                    // If you're getting SSL cert errors, add `CURLOPT_SSL_VERIFYPEER => FALSE,` after line 59 in --> vendor/twilio/sdk/services/twilio/TinyHttp.php
                    Twilio::message($user->phone_number, $description);
                }
            }
        }
        // Twilio::message($event->user->phone, $event->message);
    }
}
