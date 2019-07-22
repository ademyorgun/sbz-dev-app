<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
     * Enabling soft delete for users
     * 
     */
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    /**
     * The storage format of the model's date columns.
     *
     * @var string
     */
    protected $dateFormat = 'Y-m-d H:i:s';

     /**
     * Get the comments for the appointment.
     */
    public function comments()
    {
        return $this->hasMany('App\Comment');
    }

    /**
     * Get the user that owns the appointment.
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'created_by');
    }

    /**
     * Scope a query to only include appointments of the current month
     * 
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $selectedYear
     * @param  mixed  $selectedMonth
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSelectedMonth($query, $selectedYear, $selectedMonth) {
        return $query->whereYear('created_at', $selectedYear)
                    ->whereMonth('created_at', $selectedMonth);
    }

    /**
     * Scope a query to only include appointments where 
     * the meeting date is not null
     * 
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $isAgentMeetingDateSet
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeMeetingDate($query, $isAgentMeetingDateSet) {
        return $query->when($isAgentMeetingDateSet, function($query, $isAgentMeetingDateSet) {
                        return $query->whereNotNull('meeting_date');
                    })
                    // agent meeting date is not set
                    ->when(!$isAgentMeetingDateSet, function($query, $isAgentMeetingDateSet) {
                        return $query->whereNull('meeting_date');
                    });
    }

    /**
     * Scope a query to only include appointments won
     * 
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $isAppointmentWon
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAppointmentWon($query, $isAppointmentWon) {
        return $query->when($isAppointmentWon, function($query, $isAppointmentWon) {
                        return $query->whereNotNull('graduation_abschluss');
                    })
                    // agent meeting date is not set
                    ->when(!$isAppointmentWon, function($query, $isAppointmentWon) {
                        return $query->whereNull('graduation_abschluss');
                    });
    }
}
