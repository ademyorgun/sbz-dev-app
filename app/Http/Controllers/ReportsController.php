<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Appointment;

class ReportsController extends Controller
{
    /**
     * 
     * 
     * 
     */
    public function index() {
        return view('pages.reports.index');
    }

    /**
     * Generate the required Data for the 
     * 
     * 
     * @param request
     */
    public function show(Request $request) {
        $selectedMonth = (string)$request->input('month');
        $selectedYear = (string)$request->input('year');
        $selectedDay = (int)$request->input('day');

        $appointmentPossibleStatus = ['open', 'positive', 'negative', 'not_home', 'processing', 'multi_year_contract'];
        
        // Get all users
        $users = User::all();
        
        // Appoitments for the selected year/month
        $allAppointments = Appointment::whereYear('created_at', $selectedYear)->whereMonth('created_at', $selectedMonth)->get();
        $numOfAllApointments = count($allAppointments);

        // Number of appointments per user
        $numOfAppointmentsPerUser = [];
        foreach ($users as $key => $user) {
            $appointments = User::find($user->id)->appointments;
            $totalOfAppointments = count($appointments);

            $numOfAppointmentsPerUser[$user->user_name] = $totalOfAppointments;
        };

        // Number of appointments per day
        $numOfAppointmentsPerDay = [];
        while ($selectedDay > 0) {
            $allAppointments = Appointment::whereMonth('created_at', $selectedMonth)->whereDay('created_at', $selectedDay)->get();
            $numOfAppointmentsPerDay[$selectedDay] = count($allAppointments);
            
            $selectedDay = $selectedDay - 1;
        }

        // Number of appointments per status (  )
        $numOfAppointmentsPerStatus = [];
        foreach ($appointmentPossibleStatus as $key => $status) {
            $allAppointments = Appointment::where('comment_status', $status)->get();

            $numOfAppointmentsPerStatus[$status] = count($allAppointments);
        }

        // Returning the result
        return response()->json([
            'numOfAppointmentsPerUser' => $numOfAppointmentsPerUser,
            'numOfAllApointments' => $numOfAllApointments,
            'numOfAppointmentsPerDay' => $numOfAppointmentsPerDay,
            'numOfAppointmentsPerStatus' => $numOfAppointmentsPerStatus
        ]);
    }
}
