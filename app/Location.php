<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $table = 'locations';

    public function users()
    {
        return $this->belongsToMany('App\User', 'user_location');
    }

    public function incidents()
    {
    	return $this->hasMany('App\Incident', 'location_id')
    }
}
