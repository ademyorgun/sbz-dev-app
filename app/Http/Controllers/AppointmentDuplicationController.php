<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Appointment;

class AppointmentDuplicationController extends Controller
{
    public function store(Request $request, $id) {
        $appointment = Appointment::find($id);
        
        
        $newAppointment = $appointment->replicate();
        
        $newAppointment->duplicated_from_id = $appointment->id;
        
        $newAppointment->save();
        
        $appointment->appointment_status = 're-scheduled';
        $appointment->duplicated_to_id = $newAppointment->id;

        $appointment->save();

        dd($appointment);
    }
}
