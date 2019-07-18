<?php

namespace App\Http\Controllers;

use App\Appointment;
use Illuminate\Http\Request;

class AppointmentAssignementController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $ids = $request->input('selected_ids');
        $ids = explode(',', $ids);
        
        $appointments = Appointment::whereIn('id', $ids)->get();


        dd($ids);
    }
}
