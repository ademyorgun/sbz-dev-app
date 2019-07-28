<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appointment extends Model
{
    use SoftDeletes;
    /**
     * The attributes that should be mutated to dates.
     * 
     */
    protected $dates = [
        'deleted_at',
        'meeting_date',
        'call_date'
    ];

    /**
     * The storage format of the model's date columns.
     *
     * @var string
     */
    protected $dateFormat = 'Y-m-d H:i:s';

    /**
     * Mutator
     * Set the appointment meeting date
     * 
     * 
     */
    public function setMeetingDateAttribute($value) {
        if(isset($value)) {
            $this->attributes['meeting_date'] = \Carbon\Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d');
        }
    }

    /**
     * Mutator
     * Set the appointment call date
     * 
     * 
     */
    public function setCallDateAttribute($value) {
        $this->attributes['call_date'] = \Carbon\Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d');
    }
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
        return $query
            ->when($isAgentMeetingDateSet, function($query, $isAgentMeetingDateSet) {
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
        return $query
            ->when($isAppointmentWon, function($query, $isAppointmentWon) {
                return $query->whereNotNull('graduation_abschluss');
            })
            // agent meeting date is not set
            ->when(!$isAppointmentWon, function($query, $isAppointmentWon) {
                return $query->whereNull('graduation_abschluss');
        });
    }

    /**
     * Scope a query to only include the appointments assigned 
     * for the sales agent
     * 
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $isAppointmentWon
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSalesAgentAppointments($query) {
        if(strtolower(auth()->user()->role->name) == 'sales_agent' ) {
            return $query
                ->where('sales_agent_id', auth()->user()->id);
        } else {
            return $query;
        }
    }
}
