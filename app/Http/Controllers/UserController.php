<?php

namespace App\Http\Controllers;

use App\Calendar;
use App\Graphic;
use App\Property;
use App\User;
use App\Year;
use App\Month;
use App\Day;
use App\Appointment;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('auth');
    }
    
    /**
     * Shows employees.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function employeesList()
    {
        $employees = User::where('isEmployee', 1)->get();
        
        return view('employee.index')->with('employees', $employees);
    }
    
    /**
     * Shows employee.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function employee($slug)
    {
        $employee = User::where('isEmployee', 1)->where('slug', $slug)->first();
        $calendars = Calendar::where('employee_id', $employee->id)->where('isActive', 1)->get();
        
        $properties = [];
        
        for ($i = 0; $i < count($calendars); $i++)
        {
            $properties[$i] = Property::where('id', $calendars[$i]->property_id)->first();
        }
        
        return view('employee.show')->with('employee', $employee)->with('calendars', $calendars)->with('properties', $properties);
    }
    
    /**
     * Shows calendar that belongs to employee.
     * 
     * @param integer $calendar_id
     * @param integer $year
     * @param integer $month_number
     * 
     * @return type
     * @throws Exception
     */
    public function calendar($calendar_id, $year = 0, $month_number = 0)
    {
        $calendar = Calendar::where('id', $calendar_id)->where('isActive', 1)->first();
        $currentDate = new \DateTime();
        
        if ($calendar != null)
        {
            if ($year == 0)
            {
                $year = Year::where('calendar_id', $calendar->id)->where('year', $currentDate->format("Y"))->first();
            }
            else if (is_int($year) && $year > 0)
            {
                $year = Year::where('calendar_id', $calendar->id)->where('year', $year)->first();
            }
            else 
            {
                throw new Exception('Incorrect year number');
            }

            if ($month_number == 0)
            {
                $month = Month::where('year_id', $year->id)->where('month_number', $currentDate->format("n"))->first();
            }
            else if (is_int($month_number) && $month_number > 0 || $month_number <= 12)
            {
                $month = Month::where('year_id', $year->id)->where('month_number', $month_number)->first();
            }
            else 
            {
                throw new Exception('Incorrect month number');
            }
            
            if ($month == null)
            {
                $month = $this->switchFromMonthNumberToMonthName($currentDate->format("n"));
            }
        }        
        
        if (is_object($year) && is_object($month))
        {
            $days = Day::where('month_id', $month->id)->get();
            $days = $this->formatDaysToUserCalendarForm($days, $month->days_in_month);

            $currentDay = Day::where('month_id', $month->id)->where('day_number', $currentDate->format("d"))->first();
            
            if ($currentDay !== null)
            {
                $graphicTime = Graphic::where('day_id', $currentDay->id)->first();
                $graphic = $this->formatGraphicAndAppointments($graphicTime, $currentDay);
                
                $currentDay = $currentDay->day_number;
            }
            else
            {
                $currentDay = 0;
                $graphic = [];
            }            
        }
        else
        {
            $days = [];
            $currentDay = 0;
            $graphic = [];
        }
            
        return view('employee.calendar')->with([
            'calendar_id' => $calendar->id,
            'year' => $year,
            'month' => $month,
            'days' => $days,
            'current_day' => $currentDay,
            'graphic' => $graphic
        ]);
    }
    
    private function switchFromMonthNumberToMonthName($monthNumber)
    {
        $months = [
            '1' => 'Styczeń',
            '2' => 'Luty',
            '3' => 'Marzec',
            '4' => 'Kwiecień',
            '5' => 'Maj',
            '6' => 'Czerwiec',
            '7' => 'Lipiec',
            '8' => 'Sierpień',
            '9' => 'Wrzesień',
            '10' => 'Październik',
            '11' => 'Listopad',
            '12' => 'Grudzień'
        ];
        
        return $months[$monthNumber];
    }
    
    private function formatDaysToUserCalendarForm($days, $daysInMonth) 
    {
        $daysArray = [];
        
        for ($i = 0; $i < count($days); $i++)
        {
            if ($i == 0)
            {
                $monthStart = $days[$i]->number_in_week;
                
                if ($monthStart != 1)
                {
                    for ($j = 1; $j < $monthStart; $j++)
                    {
                        $daysArray[] = [];
                    }
                }
            }
            
            $daysArray[] = $days[$i];
        }
        
        return $daysArray;
    }
    
    private function formatGraphicAndAppointments($graphicTime, $currentDay) 
    {
        $graphic = [];
        
        if ($graphicTime !== null)
        {
            $workUnits = ($graphicTime->total_time / 60) * 2;
            $startTime = date('G:i', strtotime($graphicTime->start_time));
            
            for ($i = 0; $i < $workUnits; $i++) 
            {
                $appointment = Appointment::where('day_id', $currentDay->id)->where('start_time', $startTime)->first();
                
                if ($appointment !== null)
                {
                    $limit = $appointment->minutes / 30;
                    
                    if ($limit > 1)
                    {
                        $time = array($startTime);

                        for ($i = 1; $i < $limit; $i++)
                        {
                            $time[] = date('G:i', strtotime("+30 minutes", strtotime($time[count($time) - 1])));
                        }
                    }
                    else
                    {
                        $time = $startTime;
                    }
                    
                    $graphic[] = [
                        'time' => $time,
                        'appointment' => $limit
                    ];
                    
                    $timeIncrementedByAppointmentMinutes = strtotime($appointment->end_time, strtotime($startTime));
                    $startTime = date('G:i', $timeIncrementedByAppointmentMinutes);
                }
                else
                {
                    $graphic[] = [
                        'time' => $startTime,
                        'appointment' => 0
                    ];
                    
                    $timeIncrementedBy30Minutes = strtotime("+30 minutes", strtotime($startTime));
                    $startTime = date('G:i', $timeIncrementedBy30Minutes);
                }
            }
        }
        
        return $graphic;
    }
}
