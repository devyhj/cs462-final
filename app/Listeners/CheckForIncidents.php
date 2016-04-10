<?php
require 'vendor/autoload.php';
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
    private $curl = new \Ivory\HttpAdapter\CurlHttpAdapter();
    //private $google = new \Geocoder\Provider\GoogleMaps($curl);
    private $bingKey = Config::get('geocoder.bing.key');
    private $bing; 
    private $bingURL = "http://dev.virtualearth.net/REST/v1/Traffic/Incidents/" // + S,W,N,E?key=APIKey
    private $dumper = new \Geocoder\Dumper\GeoJson();

    public function __construct()
    {
        $this->bing =  new \Geocoder\Provider\BingMaps($curl, $this->bingKey);
    }

   /**
    *  Check MapQuest/Bing for accidents.
    *
    * @param The client to check for
    * @return void
    */
   private function checkArea($client)
   {
        $box = $this->geocoder->geocode($client->address)->getBounds();
        $report = json_decode($curl->get("{$bingURL}{$box->south - 0.007},{$box->west - 0.007},{$box->north + 0.007},{$box->east + 0.007},?key={$this->bingKey}"), true);
        if  ((int) $report->resourceSets->estimatedTotal  >= 0 ) {
            foreach ($report->resourceSets as $resources){
                foreach ($resources->resources as $accident){
                    // TODO: Check if user has already received an alert about this accident, otherwise...
                    Event::fire(new IncidentFound($client, $accident->description));
                }
            }
        }

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
        $all = DB::select('select * from users');
        foreach ($all->user as $client){
             checkArea($client);
        }
    }
}


/**
    Stripped example of  0 incidents
{
    . . .
   "resourceSets":[
      {
         "estimatedTotal":0,
         "resources":[

         ]
      }
   ],
   "statusCode":200,
   "statusDescription":"OK",
}

    Stripped example of multiple incidents

{
    . . .
   "resourceSets":[
      {
         "estimatedTotal":20,
         "resources":[
            {
               "__type":"TrafficIncident:http:\/\/schemas.microsoft.com\/search\/local\/ws\/rest\/v1",
               "point":{
                  "type":"Point",
                  "coordinates":[
                     40.87298,
                     -105.87711
                  ]
               },
               "description":"Closed between Fort Collins and CR-74E\/Jackass Rd\/Redfeather Lks Rd - Closed.",
               "end":"\/Date(1467439140000)\/",
               "incidentId":3896092144376331674,
               "lastModified":"\/Date(1459466849344)\/",
               "roadClosed":true,
               "severity":4,
               "source":4,
               "start":"\/Date(1459456796000)\/",
               "toPoint":{
                  "type":"Point",
                  "coordinates":[
                     40.791028,
                     -105.579074
                  ]
               },
               "type":5,
               "verified":true
            },
            {
               "__type":"TrafficIncident:http:\/\/schemas.microsoft.com\/search\/local\/ws\/rest\/v1",
               "point":{
                  "type":"Point",
                  "coordinates":[
                     40.57901,
                     -105.40372
                  ]
               },
               "description":"Closed between Buckhorn Ct and Pingree Park Rd - Closed.",
               "end":"\/Date(1464847140000)\/",
               "incidentId":4229937641734900902,
               "lastModified":"\/Date(1459466849344)\/",
               "roadClosed":true,
               "severity":4,
               "source":4,
               "start":"\/Date(1459456799000)\/",
               "toPoint":{
                  "type":"Point",
                  "coordinates":[
                     40.57948,
                     -105.54479
                  ]
               },
               "type":5,
               "verified":true
            },
            . . . 
         ]
      }
   ],
   "statusCode":200,
   "statusDescription":"OK",
}