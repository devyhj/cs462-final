<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Event;
use App\Events\IncidentFound;
use App\Incident;

class CheckIncidents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'incident:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checking incidents';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $bingKey = 'AunSppuTRCtKNtZ6Tw-ojcFtxuOCs7rjQDTP1X38e0RoIK-nUDzTd24bcAZUymg-';
        $bingURL = "http://dev.virtualearth.net/REST/v1/Traffic/Incidents/37.04,-115.46,42.35,-108.17"; // + S,W,N,E?key=APIKey
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $bingURL."?key=".$bingKey); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = json_decode(curl_exec($ch));
        curl_close($ch); 
        $incidents = $result->resourceSets[0]->resources;

        $newIncidents = collect();

        foreach($incidents as $incident)
        {
            // dd($incident);
            $points = $incident->point->coordinates;
            $googleKey = 'AIzaSyBY8R1d3q5xNLyJo63MGr_SINqHD7Ulgco';
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

                $newIncidents->push($newIncident);
            }
        }


        Event::fire(new IncidentFound($newIncidents));
    }
    
}
