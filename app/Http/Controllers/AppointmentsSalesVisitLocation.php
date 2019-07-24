<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Appointment; 

class AppointmentsSalesVisitLocation extends Controller
{
    /**
     * 
     * @param Illuminate\Http\Request request
     */
    public function update(Request $request, $id) {
        $address = $request->input('address');

        $appointment = Appointment::findOrFail($id);
        $appointment->sales_visit_location = $address;

        $appointment->save();

        return response()->json([
            'message' => 'Sales visit address is successfuly saved'
        ]) ;
    }
}
