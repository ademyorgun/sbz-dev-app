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
        $request->validate([
            'selected_ids' => 'required',
            'selected_agent_id' => 'required'
        ]);

        $ids = $request->input('selected_ids');
        $selected_agent_id = (int)$request->input('selected_agent_id');
        $ids = explode(',', $ids);
        
        $appointments = Appointment::whereIn('id', $ids)->get();

        foreach ($appointments as $key => $appointment) {
            $appointment->call_agent_id = $selected_agent_id;

            $appointment->save();
        }

        return redirect()
        ->route("voyager.appointments.index")
        ->with([
            'message'    => __('voyager::generic.successfully_updated'),
            'alert-type' => 'success',
        ]);
    }
}
