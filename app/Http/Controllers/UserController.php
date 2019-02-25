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
        $this->middleware('auth')->except(['employeesList', 'employee', 'propertiesList', 'property', 'calendar']);
    }
    
    /**
     * Shows employees.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function employeesList()
    {
        $employees = User::where('isEmployee', 1)->get();
        
        $employeesArray = [];
        
        for ($i = 0; $i < count($employees); $i++)
        {
            $employeesArray[$i + 1] = $employees[$i];
        }
        
        return view('employee.index')->with('employees', $employeesArray);
    }
    
    /**
     * Shows employee.
     *
     * @param type $slug
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
     * Shows properties.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function propertiesList()
    {
        $properties = Property::all();
        
        $propertiesArray = [];
        
        for ($i = 0; $i < count($properties); $i++)
        {
            $propertiesArray[$i + 1] = $properties[$i];
        }
        
        return view('user.property_index')->with('properties', $propertiesArray);
    }
    
    /**
     * Shows property.
     * 
     * @param type $slug
     * @return type
     */
    public function property($slug)
    {
        if (is_string($slug) && $slug !== null)
        {
            $property = Property::where('slug', $slug)->first();
            
            if ($property !== null)
            {
                $calendars = Calendar::where('property_id', $property->id)->where('isActive', 1)->get();
                
                $employees = [];

                if ($calendars !== null)
                {
                    for ($i = 0; $i < count($calendars); $i++)
                    {
                        $employees[$i] = User::where('id', $calendars[$i]->employee_id)->first();
                    }
                }
                
                return view('user.property_show')->with([
                    'property' => $property,
                    'employees' => $employees
                ]);
            }
        }
        
        return redirect()->route('welcome');
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
                            
                            $chosenDay = $currentDay;
                            $chosenDayDateTime = new \DateTime($year->year . "-" . $month->month_number . "-" . $chosenDay->day_number);
                            $graphic = $this->formatGraphicAndAppointments($graphicTime, $currentDay, $chosenDayDateTime);

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
                        $message = 'Brak otwartego grafiku na ten dzień';
                    }
                }
                else
                {
                    $message = 'Brak otwartego grafiku na ten miesiąc';
                }
            }
            else
            {
                $message = 'Brak otwartego grafiku na ten rok';
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
            $message = 'Niepoprawny numer id kalendarza!';
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
            $appointment = Appointment::where('id', $id)->where('user_id', auth()->user()->id)->with('item')->first();
            
            if ($appointment !== null)
            {
                $day = Day::where('id', $appointment->day_id)->first();
                $month = Month::where('id', $day->month_id)->first();
                $year = Year::where('id', $month->year_id)->first();
                
                $calendar = Calendar::where('id', $year->calendar_id)->first();
                
                $employee = User::where('id', $calendar->employee_id)->first();
                $property = Property::where('id', $calendar->property_id)->first();
                
                return view('user.appointment_show')->with([
                    'appointment' => $appointment,
                    'day' => $day->day_number,
                    'month' => $month->month,
                    'year' => $year->year,
                    'calendarId' => $calendar->id,
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
                
                $date = $day->day_number. ' ' . $month->month . ' ' . $year->year;
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
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function appointmentDestroy($id)
    {        
        $appointment = Appointment::where('id', $id)->first();
        $appointment->delete();
        
        /**
        * 
        * 
        * 
        * email sanding
        * 
        * 
        * 
        * 
        */
        
        return redirect()->action(
            'UserController@appointmentIndex'
        )->with('success', 'Wizyta została odwołana!');
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
    
    private function formatGraphicAndAppointments($graphicTime, $chosenDay, $chosenDayDateTime) 
    {
        $graphic = [];
        
        if ($graphicTime !== null)
        {
            $timeZone = new \DateTimeZone("Europe/Warsaw");
            $now = new \DateTime(null, $timeZone);
            
            $workUnits = ($graphicTime->total_time / 30);
            $startTime = date('G:i', strtotime($graphicTime->start_time));
            
            for ($i = 0; $i < $workUnits; $i++) 
            {
                $appointment = Appointment::where('day_id', $chosenDay->id)->where('start_time', $startTime)->first();
                
                $appointmentId = 0;
                
                $explodedStartTime = explode(":", $startTime);
                $chosenDayDateTime->setTime($explodedStartTime[0], $explodedStartTime[1], 0);
                
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
                        'appointmentId' => $appointmentId,
                        'canMakeAnAppointment' => $chosenDayDateTime > $now ? true : false
                    ];
                    
                    $timeIncrementedByAppointmentMinutes = strtotime($appointment->end_time, strtotime($startTime));
                    $startTime = date('G:i', $timeIncrementedByAppointmentMinutes);
                }
                else
                {
                    $graphic[] = [
                        'time' => $startTime,
                        'appointment' => 0,
                        'appointmentId' => $appointmentId,
                        'canMakeAnAppointment' => $chosenDayDateTime > $now ? true : false
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
