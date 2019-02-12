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
use App\Item;

class UserController extends Controller
{    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['employeesList', 'employee', 'calendar']);
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
     * @param integer $day_number
     * 
     * @return type
     * @throws Exception
     */
    public function calendar($calendar_id, $year = 0, $month_number = 0, $day_number = 0)
    {
        $calendar = Calendar::where('id', $calendar_id)->where('isActive', 1)->first();
        
        if ($calendar !== null)
        {
            $currentDate = new \DateTime();
            
            if ($year == 0)
            {
                $year = Year::where('calendar_id', $calendar->id)->where('year', $currentDate->format("Y"))->first();
            }
            else if (is_numeric($year) && (int)$year > 0)
            {
                $year = Year::where('calendar_id', $calendar->id)->where('year', $year)->first();
            }
            
            if ($year !== null)
            {
                if ($month_number == 0)
                {
                    $month = Month::where('year_id', $year->id)->where('month_number', $currentDate->format("n"))->first();
                }
                else if (is_numeric($month_number) && (int)$month_number > 0 || (int)$month_number <= 12)
                {
                    $month = Month::where('year_id', $year->id)->where('month_number', $month_number)->first();
                }

                if ($month !== null)
                {
                    $days = Day::where('month_id', $month->id)->get();
                    
                    if ($days !== null)
                    {
                        $days = $this->formatDaysToUserCalendarForm($days, $month->days_in_month);

                        if ((int)$day_number === 0)
                        {
                            $currentDay = Day::where('month_id', $month->id)->where('day_number', $currentDate->format("d"))->first();
                        }
                        else
                        {
                            $currentDay = Day::where('month_id', $month->id)->where('day_number', $day_number)->first();
                        }

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
                            $graphicTime = [];
                        }

                        $availablePreviousMonth = false;

                        if ($this->checkIfPreviewMonthIsAvailable($calendar, $year, $month))
                        {
                            $availablePreviousMonth = true;
                        }
                        
                        $availableNextMonth = false;

                        if ($this->checkIfNextMonthIsAvailable($calendar, $year, $month))
                        {
                            $availableNextMonth = true;
                        }
                        
                        $employee = User::where('isEmployee', 1)->where('id', $calendar->employee_id)->first();

                        return view('employee.calendar')->with([
                            'calendar_id' => $calendar->id,
                            'employee_slug' => $employee->slug,
                            'availablePreviousMonth' => $availablePreviousMonth,
                            'availableNextMonth' => $availableNextMonth,
                            'year' => $year,
                            'month' => $month,
                            'days' => $days,
                            'current_day' => $currentDay,
                            'graphic' => $graphic,
                            'graphic_id' => $graphicTime ? $graphicTime->id : null
                        ]);
                    }
                    else
                    {
                        $message = 'There is no graphic open for this month';
                    }
                }
                else
                {
                    $message = 'There is no graphic open for this month';
                }
            }
            else
            {
                $message = 'There is no graphic open for this year';
            }
            
            return redirect()->action(
                'UserController@calendar', [
                    'calendar_id' => $calendar->id,
                    'year' => 0, 
                    'month_number' => 0, 
                    'day_number' => 0
                ]
            )->with('error', $message);
        }   
        else
        {
            $message = 'Incorrect calendar id!';
        }
        
        return redirect()->action(
            'UserController@employeesList', []
        )->with('error', $message);
    }
    
    /**
     * Shows an appointment assigned to current user.
     * 
     * @param type $id
     * @return type
     */
    public function appointmentShow($id)
    {
        if ($id !== null)
        {
            $appointment = Appointment::where('id', $id)->where('user_id', auth()->user()->id)->first();
            
            if ($appointment !== null)
            {
                $item = Item::where('id', $appointment->item_id)->first();
                $day = Day::where('id', $appointment->day_id)->first();
                $month = Month::where('id', $day->month_id)->first();
                $year = Year::where('id', $month->year_id)->first();
                
                $calendar = Calendar::where('id', $year->calendar_id)->first();
                
                $employee = User::where('id', $calendar->employee_id)->first();
                $property = Property::where('id', $calendar->property_id)->first();
                
                return view('user.appointment_show')->with([
                    'appointment' => $appointment,
                    'item' => $item,
                    'day' => $day->day_number,
                    'month' => $month->month,
                    'year' => $year->year,
                    'employee' => $employee,
                    'property' => $property
                ]);
            }
        }
        
        return redirect()->route('welcome');
    }
    
    /**
     * Shows a list of appointments assigned to current user.
     * 
     * @return type
     */
    public function appointmentIndex()
    {
        $appointments = Appointment::where('user_id', auth()->user()->id)->with('item')->orderBy('created_at', 'desc')->paginate(5);
        
        if ($appointments !== null)
        {
            foreach ($appointments as $appointment)
            {
                $day = Day::where('id', $appointment->day_id)->first();
                $month = Month::where('id', $day->month_id)->first();
                $year = Year::where('id', $month->year_id)->first();
                $calendar = Calendar::where('id', $year->calendar_id)->first();
                $employee = User::where('id', $calendar->employee_id)->first();
                $property = Property::where('id', $calendar->property_id)->first();
                
                $date = $day->day_number. '/' . $month->month . '/' . $year->year;
                $appointment['date'] = $date;
                
                $address = $property->street . ' ' . $property->street_number . '/' . $property->house_number . ', ' . $property->city;
                $appointment['address'] = $address;
                
                $employee = $employee->name;
                $appointment['employee'] = $employee;
            }
            
            return view('user.appointment_index')->with([
                'appointments' => $appointments
            ]);
        }
        
        return redirect()->route('welcome');
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
            $workUnits = ($graphicTime->total_time / 30);
            $startTime = date('G:i', strtotime($graphicTime->start_time));            
            
            for ($i = 0; $i < $workUnits; $i++) 
            {
                $appointment = Appointment::where('day_id', $currentDay->id)->where('start_time', $startTime)->first();
                
                $appointmentId = 0;
                
                if ($appointment !== null && auth()->user() !== null)
                {
                    $appointmentId = $appointment->user_id == auth()->user()->id ? $appointment->id  : 0;
                }
                
                if ($appointment !== null)
                {
                    $limit = $appointment->minutes / 30;
                    
                    if ($limit > 1)
                    {
                        $time = array($startTime);

                        for ($j = 1; $j < $limit; $j++)
                        {
                            $time[] = date('G:i', strtotime("+30 minutes", strtotime($time[count($time) - 1])));
                            $workUnits -= 1;
                        }
                    }
                    else
                    {
                        $time = $startTime;
                    }
                    
                    $graphic[] = [
                        'time' => $time,
                        'appointment' => $limit,
                        'appointmentId' => $appointmentId
                    ];
                    
                    $timeIncrementedByAppointmentMinutes = strtotime($appointment->end_time, strtotime($startTime));
                    $startTime = date('G:i', $timeIncrementedByAppointmentMinutes);
                }
                else
                {
                    $graphic[] = [
                        'time' => $startTime,
                        'appointment' => 0,
                        'appointmentId' => $appointmentId
                    ];
                    
                    $timeIncrementedBy30Minutes = strtotime("+30 minutes", strtotime($startTime));
                    $startTime = date('G:i', $timeIncrementedBy30Minutes);
                }
            }            
        }
        
        return $graphic;
    }
    
    private function checkIfPreviewMonthIsAvailable($calendar, $year, $month)
    {
        if ($month->month_number == 1)
        {
            $year = Year::where('calendar_id', $calendar->id)->where('year', ($year->year - 1))->first();
            
            if ($year === null)
            {
                return false;
            }
            else
            {
                $month = Month::where('year_id', $year->id)->where('month_number', 12)->first();
                
                if ($month === null) 
                {
                    return false;
                }
            }
        }
        else
        {
            $month = Month::where('year_id', $year->id)->where('month_number', ($month->month_number - 1))->first();
                
            if ($month === null) 
            {
                return false;
            }
        }
        
        return true;
    }
    
    private function checkIfNextMonthIsAvailable($calendar, $year, $month)
    {
        if ($month->month_number == 12)
        {
            $year = Year::where('calendar_id', $calendar->id)->where('year', ($year->year + 1))->first();
            
            if ($year === null)
            {
                return false;
            }
            else
            {
                $month = Month::where('year_id', $year->id)->where('month_number', 1)->first();
                
                if ($month === null) 
                {
                    return false;
                }
            }
        }
        else
        {
            $month = Month::where('year_id', $year->id)->where('month_number', ($month->month_number + 1))->first();
                
            if ($month === null) 
            {
                return false;
            }
        }
        
        return true;
    }
}
