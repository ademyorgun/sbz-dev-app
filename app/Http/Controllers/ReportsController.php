<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use App\User;
use App\Appointment;
use App\CallCenter;
use App\Role;

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
        $selectedDay = (string) $request->input('day');
        $today = (int)$request->input('today');
        $isAgentMeetingDateSet = (boolean)$request->input('isAgentMeetingDateSet'); 
        $isAppointmentWon = (boolean)$request->input('isAppointmentWon');

        /**
         * ttips for making this fuckign controller much much 
         * cleaner
         * using Local Scopes
         * Apply DRY principle
         */
        // 
        $appointmentClosingCommentStatuses = ['open', 'positive', 'negative', 'not_home', 'processing', 'multi_year_contract', 'wollte k.t'];
        // $appointmentClosingCommentStatuses = ['offen', 'positiv', 'negativ', 'Nicht zu Hause', 'Behandlung', 'MJV', 'Wollte k.T'];
        $statusDe = [
            'open' => 'offen',
            'positive' => 'positiv',
            'negative' => 'negativ',
            'not_home' => 'Nicht zu Hause',
            'processing' => 'Behandlung',
            'multi_year_contract' => 'MJV',
            'wollte k.t' => 'Wollte k.T'
        ];

        // Appoitments for the selected year/month
        $allAppointments = Appointment::SelectedDate($selectedYear, $selectedMonth, $selectedDay)
                            ->meetingDate($isAgentMeetingDateSet)
                            ->get();
        $numOfAllApointments = count($allAppointments);

        // Number of appointments per user 
        $numOfAppointmentsPerSalesAgent = [];
        $numOfAppointmentsPerCallAgent = [];
        $salesAgentsWithAppointments = User::with(['appointments' => function ($query) use ($selectedYear, $selectedMonth, $selectedDay, $isAgentMeetingDateSet) {
                            $query->selectedDate($selectedYear, $selectedMonth, $selectedDay)
                            ->meetingDate($isAgentMeetingDateSet);
                        }])->get();


        $callAgentsWithAppointments = User::with(['callAgentsAppointments' => function ($query) use ($selectedYear, $selectedMonth, $selectedDay, $isAgentMeetingDateSet) {
                                        $query->selectedDate($selectedYear, $selectedMonth, $selectedDay)
                                            ->meetingDate($isAgentMeetingDateSet);
                                    }])->get();;

        foreach ($salesAgentsWithAppointments as $key => $user) {
            if(strtolower($user->role->name) == 'sales_agent') {
                $numOfAppointmentsPerSalesAgent[$user->user_name]['total'] = count($user->appointments);
                $numOfAppointmentsPerSalesAgent[$user->user_name]['won'] = 0;
                $numOfAppointmentsPerSalesAgent[$user->user_name]['name'] = $user->user_name;
                $numOfAppointmentsPerSalesAgent[$user->user_name]['anzahlAbschlusseTotal'] = 0;

                foreach ($user->appointments as $key => $appointment) {
                    if (isset($appointment->graduation_abschluss)) {
                        $numOfAppointmentsPerSalesAgent[$user->user_name]['won']++;
                        $numOfAppointmentsPerSalesAgent[$user->user_name]['anzahlAbschlusseTotal'] += $appointment->graduation_abschluss;
                    }
                }
            }
        };

        foreach ($callAgentsWithAppointments as $key => $user) {
            if (strtolower($user->role->name) == 'call_agent') {
                $numOfAppointmentsPerCallAgent[$user->user_name]['total'] = count($user->callAgentsAppointments);
                $numOfAppointmentsPerCallAgent[$user->user_name]['name'] = $user->user_name;

                // call center
                
            }
        }

        // Number of appointments per day
        $numOfAppointmentsPerDay = [];

        $numOfAllApointmentsPerDayPositive = [];
        $numOfAllApointmentsPerDayNegative = [];
        
        $numberOfAppointmentsWonPerDay = [];
        $numberOfAppointmentsNotWonPerDay = [];

        $numOfAppointmentsPerStatus = [];
        foreach ($appointmentClosingCommentStatuses as $key => $status) {
            $numOfAppointmentsPerStatus[$statusDe[strtolower($status)]] = 0;
        }

        if(now()->month == $selectedMonth) {
            $dayToUse = $today;
        } else {
            $dayToUse = \Carbon\Carbon::createFromDate($selectedYear, $selectedMonth)->daysInMonth;
        }

        while ($dayToUse > 0) {
            $allAppointmentsPositive = [];
            $allAppointmentsNegative = [];

            $numberOfAppointmentsNotWonPerDay[$dayToUse] = 0;
            $numberOfAppointmentsWonPerDay[$dayToUse] = 0;

            $allAppointments = Appointment::selectedDate($selectedYear, $selectedMonth, $selectedDay)
                            ->meetingDate($isAgentMeetingDateSet)
                            ->whereDay('created_at', $dayToUse)
                            ->appointmentWon($isAppointmentWon)
                            ->get();
            
            $numOfAppointmentsPerDay[$dayToUse] = count($allAppointments);

            foreach($allAppointments as $key => $appointment) {
                if( strtolower($appointment->comment_status) == 'positive') {
                    array_push($allAppointmentsPositive, $appointment);
                };
                if (strtolower($appointment->comment_status) == 'negative') {
                    array_push($allAppointmentsNegative, $appointment);
                };
                // won or not won
                if ($appointment->graduation_abschluss == null) {
                    $numberOfAppointmentsNotWonPerDay[$dayToUse]++;
                };
                if($appointment->graduation_abschluss != null) {
                    $numberOfAppointmentsWonPerDay[$dayToUse]++;
                };

                // Num of appointment per status 
                foreach ($appointmentClosingCommentStatuses as $key => $status) {
                    if( strtolower($appointment->comment_status) == strtolower($status) ) {
                        if(isset($statusDe[strtolower($status)])) {
                            $numOfAppointmentsPerStatus[$statusDe[strtolower($status)]]++;
                        }
                    }
                }
            }

            $numOfAllApointmentsPerDayPositive[$dayToUse] = count($allAppointmentsPositive);
            $numOfAllApointmentsPerDayNegative[$dayToUse] = count($allAppointmentsNegative);
            
            $dayToUse = $dayToUse - 1;
        }

        // call centers
        $callCenters = CallCenter::with(['appointments' => function($query) use ($selectedYear, $selectedMonth, $selectedDay, $isAgentMeetingDateSet) {
            // for some reason we have to do it this way rather than using the already defined scopes
            $query->whereYear('appointments.created_at', $selectedYear)
                ->whereMonth('appointments.created_at', $selectedMonth)
                ->when($selectedDay, function($query, $selectedDay) {
                    return $query->whereDay('appointments.created_at', $selectedDay);
                })
                ->when($isAgentMeetingDateSet, function ($query, $isAgentMeetingDateSet) {
                    return $query->whereNotNull('appointments.meeting_date');
                })
                ->when(!$isAgentMeetingDateSet, function ($query, $isAgentMeetingDateSet) {
                    return $query->whereNull('appointments.meeting_date');
                });
        }])->get();
        foreach ($callCenters as $key => $callCenter) {
            $callCenter->totalAppointments = count($callCenter->appointments->toArray());
            // filter the won appointments
            $callCenter->wonAppointments = $callCenter->appointments->filter(function($item, $key) {
                if($item->graduation_abschluss !== null) {
                    return true;
                }
            });
            // won appointments num
            $callCenter->wonAppointments = count($callCenter->wonAppointments->toArray());
        }

        // Returning the result
        return response()->json([
            'numOfAppointmentsPerSalesAgent' => $numOfAppointmentsPerSalesAgent,
            'numOfAppointmentsPerCallAgent' => $numOfAppointmentsPerCallAgent,
            'numOfAllApointments' => $numOfAllApointments,
            'numOfAppointmentsPerDay' => $numOfAppointmentsPerDay,
            'numOfAppointmentsPerStatus' => $numOfAppointmentsPerStatus,
            'numOfAllApointmentsPerDayPositive' => $numOfAllApointmentsPerDayPositive,
            'numOfAllApointmentsPerDayNegative' => $numOfAllApointmentsPerDayNegative,
            'numberOfAppointmentsWonPerDay' => $numberOfAppointmentsWonPerDay,
            'numberOfAppointmentsNotWonPerDay' => $numberOfAppointmentsNotWonPerDay,
            'callCenters' => $callCenters
        ]);
    }
}
