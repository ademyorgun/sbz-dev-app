<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CallCenter extends Model
{
    
    public function users() {
        return $this->hasMany('App\User', 'call_center_id');
    }
}
