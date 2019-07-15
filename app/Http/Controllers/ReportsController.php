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
        $allAppointments = Appointment::whereYear('created_at', $selectedYear)
                                        ->whereMonth('created_at', $selectedMonth)
                                        ->get();
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
        $dayToUse = $selectedDay; // we gonna need the selectedDay value later
        while ($dayToUse > 0) {
            $allAppointments = Appointment::whereMonth('created_at', $selectedMonth)
                                            ->whereDay('created_at', $dayToUse)
                                            ->get();
            $numOfAppointmentsPerDay[$dayToUse] = count($allAppointments);
            
            $dayToUse = $dayToUse - 1;
        }

        // Number of appointments per status
        $numOfAppointmentsPerStatus = [];
        foreach ($appointmentPossibleStatus as $key => $status) {
            $allAppointments = Appointment::where('comment_status', $status)->get();

            $numOfAppointmentsPerStatus[$status] = count($allAppointments);
        }

        // Number of appointments per Day with a positive and negative status
        $numOfAllApointmentsPerDayPositive = [];
        $numOfAllApointmentsPerDayNegative = [];
        while($selectedDay > 0) {
            $allAppointmentsPositive = Appointment::whereMonth('created_at', $selectedMonth)
                                    ->whereDay('created_at', $selectedDay)
                                    ->where('comment_status', 'positive')
                                    ->get();
            $allAppointmentsNegative = Appointment::whereMonth('created_at', $selectedMonth)
                                    ->whereDay('created_at', $selectedDay)
                                    ->where('comment_status', 'negative')
                                    ->get();

            $numOfAllApointmentsPerDayPositive[$selectedDay] = count($allAppointmentsPositive);
            $numOfAllApointmentsPerDayNegative[$selectedDay] = count($allAppointmentsNegative);

            $selectedDay = $selectedDay -1;
        }
        // Returning the result
        return response()->json([
            'numOfAppointmentsPerUser' => $numOfAppointmentsPerUser,
            'numOfAllApointments' => $numOfAllApointments,
            'numOfAppointmentsPerDay' => $numOfAppointmentsPerDay,
            'numOfAppointmentsPerStatus' => $numOfAppointmentsPerStatus,
            'numOfAllApointmentsPerDayPositive' => $numOfAllApointmentsPerDayPositive,
            'numOfAllApointmentsPerDayNegative' => $numOfAllApointmentsPerDayNegative
        ]);
    }
}