<?php

namespace App\Listeners;

use App\Events\IncidentFound;
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
            $url = 'https://maps.googleapis.com/maps/api/geocode/json?latlng='.$points[0].','.$points[1].'&key=AIzaSyBY8R1d3q5xNLyJo63MGr_SINqHD7Ulgco';
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url); 
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $result = json_decode(curl_exec($ch));
            curl_close($ch);
            // dd($result);
            $external_id = $incident->incidentId;
            $description = $incident->description;
            $address = $result->results[0]->formatted_address;
            $count = Incident::where('external_id', $external_id)->count();

            if($count == 0) //if new incident
            {
                $newIncident = new Incident;
                $newIncident->location = $address;
                $newIncident->description = $description;
                $newIncident->external_id=$external_id;
                $newIncident->save();
            }
        }
        // Twilio::message($event->user->phone, $event->message);
    }
}
