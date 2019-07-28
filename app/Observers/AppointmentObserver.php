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
        //apointment was edited and without a visit date
        if($appointment->meeting_date == null) {
            $appointment->appointment_status = 'erstellt'; // created
        } else {
            $appointment->appointment_status = 'geplant'; // planned
        }
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
            $appointment->appointment_status = 'Neu geplant'; // re-sechduled
        } else {
            // appointment was edited and without a visit date
             if($appointment->meeting_date == null) {
                $appointment->appointment_status = 'ungeplant'; // unplanned
            } else {
                if(isset($appointment->sales_agent_id)) {
                    $appointment->appointment_status = 'zugeteilt'; // assigned
                } else{
                    $appointment->appointment_status = 'nicht zugeteilt'; // not assigned
                }
            }
        }

        // Appointment has any status
        if( $appointment->comment_status != null) {
            $appointment->appointment_status = 'abgeschlossen'; // closed
        }   
        
        // Appointment has a geo tracking location saved
        if( $appointment->sales_visit_location != null ) {
            $appointment->appointment_status = 'besucht'; // visited
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
        $appointment->appointment_status = "gelöscht"; // deleted
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
