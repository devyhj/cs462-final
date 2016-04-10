<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Incident extends Model
{
    protected $table = 'incidents';

    public function location()
    {
    	return $this->belongsTo('App\Location', 'location_id');
    }
}
