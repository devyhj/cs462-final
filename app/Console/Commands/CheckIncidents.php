<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Event;
use App\Events\IncidentFound;

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
        Event::fire(new IncidentFound($incidents));
    }
    
}
