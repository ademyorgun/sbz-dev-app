<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Appointment;

class AppointmentDuplicationController extends Controller
{
    public function store(Request $request, $id) {
        $appointment = Appointment::find($id);

        // appointment already duplicated so we stop
        if($appointment->duplicated_to_id != null) {
            return redirect()
                ->route("voyager.appointments.index")
                ->with([
                    'message'    => "Appointment already duplicated",
                    'alert-type' => 'error',
                ]);
        };
          
        // the cloned appointment
        $newAppointment = $appointment->replicate();
        //push to get the id for the cloned record
        $newAppointment->push();
        $newAppointment->duplicated_from_id = $appointment->id;
        $newAppointment->appointment_status = null;
        $newAppointment->save();
        
        // updating the old appointment
        $appointment->duplicated_to_id = $newAppointment->id;
        $appointment->save();

        return redirect()
            ->route("voyager.appointments.index")
            ->with([
                'message'    => "Appointment successfuly duplicated",
                'alert-type' => 'success',
            ]);
    }
}
