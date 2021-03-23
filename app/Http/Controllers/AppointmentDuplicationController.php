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
        $newAppointment = $appointment->replicate()->fill([
            'duplicated_from_id' => $appointment->id,
            'appointment_status' => null,
        ]);
        $newAppointment->push();
        $newAppointment->save();
        // updating the old appointment
        $appointment->update([
            'duplicated_to_id' => $newAppointment->id,
        ]);

        return redirect()
            ->route("voyager.appointments.index")
            ->with([
                'message'    => "Appointment successfuly duplicated",
                'alert-type' => 'success',
            ]);
    }
}
