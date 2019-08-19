<?php

namespace App;

use Carbon\Carbon;
use App\Scopes\CallAgentsScope;
use App\Scopes\SalesAgentsScope;
use App\Scopes\OrderingAppointmentsScope;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new CallAgentsScope);
        static::addGlobalScope(new SalesAgentsScope);
        static::addGlobalScope(new OrderingAppointmentsScope);
    }

    /**
     * The attributes that should be mutated to dates.
     * 
     */
    protected $dates = [
        'meeting_date',
        'call_date'
    ];

    protected $perPage = 15;

    /**
     * The storage format of the model's date columns.
     *
     * @var string
     */
    protected $dateFormat = 'Y-m-d H:i:s';

    /**
     * Mutator
     * Set the appointment meeting date
     * the reason for needing this mutator is that
     * mysql only accepts one date format for storing dates
     * so we have to change the format of the date before storing it
     * 
     */
    public function setMeetingDateAttribute($value) {
        if(isset($value)) {
            $this->attributes['meeting_date'] = \Carbon\Carbon::createFromFormat('d-m-Y', $value)->format('Y-m-d');
        }
    }

    /**
     * Mutator
     * Set the appointment call date
     * 
     * 
     */
    public function setCallDateAttribute($value) {
        $this->attributes['call_date'] = \Carbon\Carbon::createFromFormat('d-m-Y', $value)->format('Y-m-d');
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
     * Scope filter appointemtns
     * 
     * 
     */
    public function scopeFilterAppointments($query, $request) {
        $appointmentID = $request->input('appointmentID');
        $phoneNumber = $request->input('phoneNumber');
        $userID = $request->input('userID');
        $canton = $request->input('canton');
        $wantedExpert = $request->input('wantedExpert');
        $appointmentDateEnd = $request->input('appointmentDateEnd');
        $appointmentDateStart = $request->input('appointmentDateStart');
        $callDateEnd = $request->input('callDateEnd');
        $callDateStart = $request->input('callDateStart');

        if ($appointmentDateEnd != null) {
            $appointmentDateEnd = Carbon::parse($appointmentDateEnd, 'Europe/London')->format('Y-m-d');
        }
        if ($appointmentDateStart != null) {
            $appointmentDateStart = Carbon::parse($appointmentDateStart, 'Europe/London')->format('Y-m-d');
        }
        if ($callDateEnd != null) {
            $callDateEnd = Carbon::parse($callDateEnd, 'Europe/London')->format('Y-m-d');
        }
        if ($callDateStart != null) {
            $callDateStart = Carbon::parse($callDateStart, 'Europe/London')->format('Y-m-d');
        }

        return $query->when($appointmentID, function ($data, $appointmentID) {
                return $data->where('id', '=', $appointmentID);
            })
            ->when($phoneNumber, function ($data, $phoneNumber) {
                return $data->where('telephone_number', '=', $phoneNumber);
            })
            ->when($userID, function ($data, $userID) {
                return $data->where('sales_agent_id', '=', $userID);
            })
            ->when($canton, function ($data, $canton) {
                return $data->where('canton_city', '=', $canton);
            })
            ->when($wantedExpert, function ($data, $wantedExpert) {
                return $data->where('wanted_expert', '=', $wantedExpert);
            })
            ->when($appointmentDateEnd, function ($data, $appointmentDateEnd) {
                return $data->where('meeting_date', '<=', $appointmentDateEnd);
            })
            ->when($appointmentDateStart, function ($data, $appointmentDateStart) {
                return $data->where('meeting_date', '>=', $appointmentDateStart);
            })
            ->when($callDateEnd, function ($data, $callDateEnd) {
                return $data->where('call_date', '<=', $callDateEnd);
            })
            ->when($callDateStart, function ($data, $callDateStart) {
                return $data->where('call_date', '>=', $callDateStart);
            });
    }
}
