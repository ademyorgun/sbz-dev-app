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

        if(isset($appointment->sales_visit_location)) {
            return response()->json([
                'message' => 'Sales visit location already exists',
                'alertType' => 'error'
            ]);
        } else {
            $appointment->sales_visit_location = $address;
            $appointment->save();
    
            return response()->json([
                'message' => 'Sales visit address is successfuly saved',
                'alertType' => 'success'
            ]) ;
        }
    }
}
