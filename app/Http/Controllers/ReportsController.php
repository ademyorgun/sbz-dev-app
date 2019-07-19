<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
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
        $isAgentMeetingDateSet = (boolean)$request->input('isAgentMeetingDateSet'); 

        /**
         * ttips for making this fuckign controller much much 
         * cleaner
         * using Local Scopes
         * Apply DRY principle
         */
        // 
        $appointmentPossibleStatus = ['open', 'positive', 'negative', 'not_home', 'processing', 'multi_year_contract'];
        
        // Appoitments for the selected year/month
        $allAppointments = Appointment::whereYear('created_at', $selectedYear)
                            ->whereMonth('created_at', $selectedMonth)
                            ->when($isAgentMeetingDateSet, function($query, $isAgentMeetingDateSet) {
                                return $query->whereNotNull('meeting_date');
                            })
                            // agent meeting date is not set
                            ->when(!$isAgentMeetingDateSet, function($query, $isAgentMeetingDateSet) {
                                return $query->whereNull('meeting_date');
                            })
                            ->get();
        $numOfAllApointments = count($allAppointments);

        // Number of appointments per user
        $numOfAppointmentsPerUser = [];
        $users = User::with(['appointments' => function ($query) use ($selectedYear, $selectedMonth, $isAgentMeetingDateSet) {
                            $query->whereYear('created_at', $selectedYear)
                            ->whereMonth('created_at', $selectedMonth)
                            ->when($isAgentMeetingDateSet, function($query, $isAgentMeetingDateSet) {
                                return $query->whereNotNull('meeting_date');
                            })
                            // agent meeting date is not set
                            ->when(!$isAgentMeetingDateSet, function($query, $isAgentMeetingDateSet) {
                                return $query->whereNull('meeting_date');
                            });
                        }])->get();
        foreach ($users as $key => $user) {
            $numOfAppointmentsPerUser[$user->user_name]['total'] = count($user->appointments);
            $numOfAppointmentsPerUser[$user->user_name]['won'] = 0;

            foreach ($user->appointments as $key => $appointment) {
                if( $appointment->graduation_abschluss != null ) {
                    $numOfAppointmentsPerUser[$user->user_name]['won']++;
                }
            }
        };

        // Number of appointments per day
        $numOfAppointmentsPerDay = [];
        $dayToUse = $selectedDay; // we gonna need the selectedDay value later
        while ($dayToUse > 0) {
            $allAppointments = Appointment::whereYear('created_at', $selectedYear)
                            ->whereMonth('created_at', $selectedMonth)
                            ->when($isAgentMeetingDateSet, function($query, $isAgentMeetingDateSet) {
                                return $query->whereNotNull('meeting_date');
                            })
                            // agent meeting date is not set
                            ->when(!$isAgentMeetingDateSet, function($query, $isAgentMeetingDateSet) {
                                return $query->whereNull('meeting_date');
                            })
                            ->whereDay('created_at', $dayToUse)
                            ->get();
            
            $numOfAppointmentsPerDay[$dayToUse] = count($allAppointments);

            
            $dayToUse = $dayToUse - 1;
        }

        // Number of appointments per status
        $numOfAppointmentsPerStatus = [];
        foreach ($appointmentPossibleStatus as $key => $status) {
            $allAppointments = Appointment::whereYear('created_at', $selectedYear)
                            ->whereMonth('created_at', $selectedMonth)
                            ->when($isAgentMeetingDateSet, function($query, $isAgentMeetingDateSet) {
                                return $query->whereNotNull('meeting_date');
                            })
                            // agent meeting date is not set
                            ->when(!$isAgentMeetingDateSet, function($query, $isAgentMeetingDateSet) {
                                return $query->whereNull('meeting_date');
                            })
                            ->where('comment_status', $status)->get();

            $numOfAppointmentsPerStatus[$status] = count($allAppointments);
        }

        // Number of appointments per Day with a positive and negative status
        $numOfAllApointmentsPerDayPositive = [];
        $numOfAllApointmentsPerDayNegative = [];
        $dayToUse = $selectedDay;
        while($dayToUse > 0) {
            $allAppointmentsPositive = Appointment::whereYear('created_at', $selectedYear)
                                    ->whereMonth('created_at', $selectedMonth)
                                    ->when($isAgentMeetingDateSet, function($query, $isAgentMeetingDateSet) {
                                        return $query->whereNotNull('meeting_date');
                                    })
                                    // agent meeting date is not set
                                    ->when(!$isAgentMeetingDateSet, function($query, $isAgentMeetingDateSet) {
                                        return $query->whereNull('meeting_date');
                                    })
                                    ->whereDay('created_at', $dayToUse)
                                    ->where('comment_status', 'positive')
                                    ->get();
            $allAppointmentsNegative = Appointment::whereYear('created_at', $selectedYear)
                                    ->whereMonth('created_at', $selectedMonth)
                                    ->when($isAgentMeetingDateSet, function($query, $isAgentMeetingDateSet) {
                                        return $query->whereNotNull('meeting_date');
                                    })
                                    // agent meeting date is not set
                                    ->when(!$isAgentMeetingDateSet, function($query, $isAgentMeetingDateSet) {
                                        return $query->whereNull('meeting_date');
                                    })
                                    ->whereDay('created_at', $dayToUse)
                                    ->where('comment_status', 'negative')
                                    ->get();

            $numOfAllApointmentsPerDayPositive[$dayToUse] = count($allAppointmentsPositive);
            $numOfAllApointmentsPerDayNegative[$dayToUse] = count($allAppointmentsNegative);

            $dayToUse = $dayToUse -1;
        }

        // Number of appoitments won per day
        $numberOfAppointmentsWonPerDay = [];
        $dayToUse = $selectedDay;
        while($dayToUse > 0) {
            $allAppointments = Appointment::whereYear('created_at', $selectedYear)
                                    ->whereMonth('created_at', $selectedMonth)
                                    ->when($isAgentMeetingDateSet, function($query, $isAgentMeetingDateSet) {
                                        return $query->whereNotNull('meeting_date');
                                    })
                                    // agent meeting date is not set
                                    ->when(!$isAgentMeetingDateSet, function($query, $isAgentMeetingDateSet) {
                                        return $query->whereNull('meeting_date');
                                    })
                                    ->whereDay('created_at', $dayToUse)
                                    ->whereNotNull('graduation_abschluss')
                                    ->get();

            $numberOfAppointmentsWonPerDay[$dayToUse] =count($allAppointments);   
            $dayToUse = $dayToUse -1;                  
        }

        // Number of appointments won per agnet
        $numberOfAppointmentsWonPerDay= [];
        $numberOfAppointmentsNotWonPerDay = [];
        $dayToUse = $selectedDay;
        while($dayToUse > 0) {
            $numberOfAppointmentsNotWonPerDay[$dayToUse] = 0;
            $numberOfAppointmentsWonPerDay[$dayToUse] = 0;

            $appointments = Appointment::whereYear('created_at', $selectedYear)
                                ->whereMonth('created_at', $selectedMonth)
                                ->when($isAgentMeetingDateSet, function($query, $isAgentMeetingDateSet) {
                                    return $query->whereNotNull('meeting_date');
                                })
                                // agent meeting date is not set
                                ->when(!$isAgentMeetingDateSet, function($query, $isAgentMeetingDateSet) {
                                    return $query->whereNull('meeting_date');
                                })
                                ->whereDay('created_at', $dayToUse)
                                ->get();

            foreach ($appointments as $key => $appointment) {
                // won or not won
                if($appointment->graduation_abschluss == null) {
                    $numberOfAppointmentsNotWonPerDay[$dayToUse]++;
                } else {
                    $numberOfAppointmentsWonPerDay[$dayToUse]++;
                };
            };

            $dayToUse = $dayToUse - 1;
        }

        // Returning the result
        return response()->json([
            'numOfAppointmentsPerUser' => $numOfAppointmentsPerUser,
            'numOfAllApointments' => $numOfAllApointments,
            'numOfAppointmentsPerDay' => $numOfAppointmentsPerDay,
            'numOfAppointmentsPerStatus' => $numOfAppointmentsPerStatus,
            'numOfAllApointmentsPerDayPositive' => $numOfAllApointmentsPerDayPositive,
            'numOfAllApointmentsPerDayNegative' => $numOfAllApointmentsPerDayNegative,
            'numberOfAppointmentsWonPerDay' => $numberOfAppointmentsWonPerDay,
            'numberOfAppointmentsNotWonPerDay' => $numberOfAppointmentsNotWonPerDay
        ]);
    }
}
