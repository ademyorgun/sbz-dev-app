<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    // protected $dates = [
    //     'meeting_date',
    //     'call_date'
    // ];

    /**
     * The storage format of the model's date columns.
     *
     * @var string
     */
    protected $dateFormat = 'Y-m-d H:i:s';
}
