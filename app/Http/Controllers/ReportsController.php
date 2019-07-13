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
        
        // Appoitments for the selected year/month
        $allAppointments = Appointment::whereYear('created_at', $selectedYear)->whereMonth('created_at', $selectedMonth)->get();
        $numOfAllApointments = count($allAppointments);

    }
}
