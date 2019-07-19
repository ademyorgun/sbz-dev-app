<?php

namespace App\Observers;

use App\Appointment;

class AppointmentObserver
{
    /**
     * Handle the appointment "creating" event.
     *
     * @param  \App\Appointment  $appointment
     * @return void
     */
    public function creating(Appointment $appointment)
    {
        
    }
    /**
     * Handle the appointment "created" event.
     *
     * @param  \App\Appointment  $appointment
     * @return void
     */
    public function created(Appointment $appointment)
    {
        //
    }

    /**
     * Handle the appointment "updated" event.
     *
     * @param  \App\Appointment  $appointment
     * @return void
     */
    public function updating(Appointment $appointment)
    {
        // appointment reschduled
        if($appointment->duplicated_to_id != null) {
            $appointment->appointment_status = 're-scheduled';
        }
    }

    /**
     * Handle the appointment "updated" event.
     *
     * @param  \App\Appointment  $appointment
     * @return void
     */
    public function updated(Appointment $appointment)
    {
        //
    }

    /**
     * Handle the appointment "deleting" event.
     *
     * @param  \App\Appointment  $appointment
     * @return void
     */
    public function deleting(Appointment $appointment)
    {
        //
    }

    /**
     * Handle the appointment "deleted" event.
     *
     * @param  \App\Appointment  $appointment
     * @return void
     */
    public function deleted(Appointment $appointment)
    {
        //
    }

    /**
     * Handle the appointment "restored" event.
     *
     * @param  \App\Appointment  $appointment
     * @return void
     */
    public function restored(Appointment $appointment)
    {
        //
    }

    /**
     * Handle the appointment "force deleted" event.
     *
     * @param  \App\Appointment  $appointment
     * @return void
     */
    public function forceDeleted(Appointment $appointment)
    {
        //
    }
}
