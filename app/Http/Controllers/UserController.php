<?php

namespace App\Http\Controllers;

use App\Calendar;
use App\Property;
use App\User;
use App\Year;
use App\Month;
use App\Day;

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
    public function employee($slack)
    {
        $employee = User::where('isEmployee', 1)->where('slack', $slack)->first();
        $calendars = Calendar::where('employee_id', $employee->id)->get();
        
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
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function calendar($calendar_id)
    {
        $currentDate = new \DateTime();
        $calendar = Calendar::where('id', $calendar_id)->first();
        $year = Year::where('calendar_id', $calendar->id)->where('year', $currentDate->format("Y"))->first();
        $month = Month::where('year_id', $year->id)->where('month_number', $currentDate->format("n"))->first();
        $days = Day::where('month_id', $month->id)->get();
        
        $days = $this->formatDaysToUserCalendarForm($days, $month->days_in_month);
        
        return view('employee.calendar')->with([
            'calendar_id' => $calendar->id,
            'year' => $year,
            'month' => $month,
            'days' => $days,
            'current_day' => $currentDate->format("d")
        ]);
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
}
