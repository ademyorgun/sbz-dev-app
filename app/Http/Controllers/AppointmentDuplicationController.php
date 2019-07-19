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
        $newAppointment->duplicated_from_id = $appointment->id;
        $newAppointment->save();
        
        // updating the old appointment
        $oldAppointmentId = $newAppointment->id;
        $appointment = Appointment::find($id);
        $appointment->duplicated_to_id = $oldAppointmentId;
        $appointment->save();

        return redirect()
            ->route("voyager.appointments.index")
            ->with([
                'message'    => "Appointment successfuly duplicated",
                'alert-type' => 'success',
            ]);
    }
}
