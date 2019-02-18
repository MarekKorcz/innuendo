<?php

namespace App\Http\Controllers;

use App\Calendar;
use App\Category;
use App\Item;
use App\Graphic;
use App\User;
use App\Year;
use App\Month;
use App\Day;
use App\Appointment;
use App\Property;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class WorkerController extends Controller
{    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('employee');
    }
    
    /**
     * Shows graphics list.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function graphicList()
    {
        $calendars = Calendar::where('employee_id', auth()->user()->id)->with('property')->get();
        
        if ($calendars !== null)
        {
            return view('employee.backend_graphic')->with('calendars', $calendars);
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
    public function backendCalendar($calendar_id, $year = 0, $month_number = 0, $day_number = 0)
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

                        return view('employee.backend_calendar')->with([
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
     * Shows an appointment employee or admin.
     * 
     * @param type $id
     * @return type
     */
    public function backendAppointmentShow($id)
    {
        if ($id !== null)
        {
            $appointment = Appointment::where('id', $id)->with('item')->with('user')->first();
            
            if ($appointment !== null)
            {
                $day = Day::where('id', $appointment->day_id)->first();
                $month = Month::where('id', $day->month_id)->first();
                $year = Year::where('id', $month->year_id)->first();
                
                $calendar = Calendar::where('id', $year->calendar_id)->first();
                
                $employee = User::where('id', $calendar->employee_id)->first();
                $property = Property::where('id', $calendar->property_id)->first();
                
                $statuses = [];
                
                for($i = 0; $i < 4; $i++)
                {
                    $statuses[] = [
                        'key' => $i,
                        'value' => config('appointment-status.' . $i),
                        'isActive' => $appointment->status == $i ? true : false
                    ];
                }
                
                return view('employee.backend_appointment_show')->with([
                    'appointment' => $appointment,
                    'day' => $day->day_number,
                    'month' => $month->month,
                    'year' => $year->year,
                    'calendarId' => $calendar->id,
                    'employee' => $employee,
                    'property' => $property,
                    'statuses' => $statuses
                ]);
            }
        }
        
        return redirect()->route('welcome');
    }
    
    /**
     * Shows a list of appointments assigned to user.
     * 
     * @param type $id
     * @return type
     */
    public function backendAppointmentIndex($id)
    {
        $appointments = Appointment::where('user_id', $id)->with('item')->orderBy('created_at', 'desc')->paginate(5);
        
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
                
                $appointment['name'] = $property->name;
                
                $employee = $employee->name;
                $appointment['employee'] = $employee;
            }
            
            return view('employee.backend_appointment_index')->with([
                'appointments' => $appointments
            ]);
        }
        
        return redirect()->route('welcome');
    }
    
    public function setAppointmentStatus(Request $request)
    {
        if ($request->request->all())
        {
            $appointment = Appointment::where('id', $request->get('appointmentId'))->first();
            
            if ($appointment !== null)
            {
                $appointment->status = $request->get('statusId');
                $appointment->save();
                
                $data = [
                    'type'    => 'success',
                    'message' => 'Status wizyty został zmieniony!',
                    'status'  => config('appointment-status.' . $appointment->status)
                ];
                
                return new JsonResponse($data, 200, array(), true);
                
            } else {
                
                $message = "Wizyta nie istnieje";
            }
            
        } else {
            
            $message = "Pusty request";
        }
        
        return new JsonResponse(array(
            'type'    => 'error',
            'message' => $message            
        ));
    }
    
    /**
     * An intermediate method which decides whether user has to log in or is already logged in.
     * 
     * @param Request $request
     * 
     * @return type
     */
    public function beforeShowCreatePage(Request $request)
    {
        if ($request->appointmentTerm && 
            $request->graphicId && 
            $request->calendarId && 
            $request->year && 
            $request->month && 
            $request->day)
        {
            session([
                'appointmentTerm' => $request->appointmentTerm,
                'graphicId' => $request->graphicId,
                'calendarId' => $request->calendarId,
                'year' => $request->year,
                'month' =>  $request->month,
                'day' => $request->day
            ]);
            
            if (auth()->user() !== null && auth()->user()->isEmployee)
            {
                return redirect()->action(
                    'WorkerController@appointmentCreate'
                );
                
            } else {
                
                return redirect()->route('login');
            }
        }
        
        return redirect()->route('welcome');
    }
    
    /**
     * Show the form for creating a new resource.
     * 
     * @param Request $request
     * 
     * @return type
     */
    public function appointmentCreate(Request $request)
    {
        if ($request->session()->get('appointmentTerm') !== null && is_integer((int)$request->session()->get('appointmentTerm')) &&
            $request->session()->get('graphicId') !== null && is_integer((int)$request->session()->get('graphicId')) &&
            $request->session()->get('calendarId') !== null && is_integer((int)$request->session()->get('calendarId')) &&
            $request->session()->get('year') !== null && is_integer((int)$request->session()->get('year')) &&
            $request->session()->get('month') !== null && is_integer((int)$request->session()->get('month')) &&
            $request->session()->get('day') !== null && is_integer((int)$request->session()->get('day')))
        {           
            $explodedAppointmentTerm = explode(":", $request->session()->get('appointmentTerm'));
            
            if (count($explodedAppointmentTerm) == 2)
            {
                $appointmentTerm = htmlentities($request->session()->get('appointmentTerm'), ENT_QUOTES, "UTF-8");
                $graphicId = htmlentities((int)$request->session()->get('graphicId'), ENT_QUOTES, "UTF-8");
                $calendarId = htmlentities((int)$request->session()->get('calendarId'), ENT_QUOTES, "UTF-8");
                $year = htmlentities((int)$request->session()->get('year'), ENT_QUOTES, "UTF-8");
                $month = htmlentities((int)$request->session()->get('month'), ENT_QUOTES, "UTF-8");
                $day = htmlentities((int)$request->session()->get('day'), ENT_QUOTES, "UTF-8");
                
                $request->session()->forget('appointmentTerm');
                $request->session()->forget('graphicId');
                $request->session()->forget('calendarId');
                $request->session()->forget('year');
                $request->session()->forget('month');
                $request->session()->forget('day');

                $graphic = Graphic::find($graphicId);
        
                if ($graphic !== null)
                {
                    $startTime = date('G:i', strtotime($graphic->start_time));
                    $endTime = date('G:i', strtotime($graphic->end_time));
                    $appointmentTerm = date('G:i', strtotime($appointmentTerm));

                    if ((int)$appointmentTerm >= (int)$startTime && (int)$appointmentTerm < (int)$endTime)
                    {
                        $chosenAppointment = Appointment::where('graphic_id', $graphicId)->where('start_time', $appointmentTerm)->first();

                        if ($chosenAppointment == null)
                        {
                            $appointmentLength = 1;
                            $appointmentTermIncremented = $appointmentTerm;

                            for ($i = 0; $i < 2; $i++)
                            {
                                $appointmentTermIncremented = date('G:i', strtotime("+30 minutes", strtotime($appointmentTermIncremented)));

                                if ((int)$appointmentTermIncremented >= (int)$startTime && (int)$appointmentTermIncremented < (int)$endTime)
                                {
                                    $nextAppointmentAvailable = Appointment::where('graphic_id', $graphicId)->where('start_time', $appointmentTermIncremented)->first();

                                    if ($nextAppointmentAvailable === null)
                                    {
                                        $appointmentLength += 1;
                                    }
                                    else
                                    {
                                        break;
                                    }
                                }
                                else
                                {
                                    break;
                                }
                            }

                            $calendar = Calendar::find($calendarId)->first();

                            if ($calendar !== null)
                            {
                                $category = Category::where('property_id', $calendar->property_id)->first();

                                if ($category !== null)
                                {                                    
                                    $appointmentLengthInMinutes = $appointmentLength * 30;
                                    
                                    $items = Item::where('category_id', $category->id)->where('minutes', '<=', $appointmentLengthInMinutes)->get();
                                }
                            }
                            
                            $users = User::where('isAdmin', null)->where('isEmployee', null)->pluck('name', 'id');
                            
                            return view('employee.backend_appointment_create')->with([
                                'appointmentTerm' => $appointmentTerm,
                                'calendarId' => $calendarId,
                                'graphicId' => $graphicId,
                                'year' => $year,
                                'month' => $month,
                                'day' => $day,
                                'items' => count($items) > 0 ? $items : [],
                                'users' => $users
                            ]);
                        }
                        else
                        {
                            $message = 'Wizyta jest już zajęta';
                        }
                    }
                    else
                    {
                        $message = 'Niepoprawny termin wizyty';
                    }
                }
                else
                {
                    $message = 'Grafik nie istnieje';
                }

                return redirect()->action(
                    'UserController@calendar', [
                        'calendar_id' => $calendarId,
                        'year' => $year, 
                        'month_number' => $month, 
                        'day_number' => $day
                    ]
                )->with('error', $message);
            }            
        }
        
        return redirect()->route('welcome');
    }
    
    /**
     * Store a newly created resource in storage.
     * 
     * @param Request $request
     * @return type
     */
    public function appointmentStore(Request $request)
    {
        $appointmentTerm = $request->get('appointmentTerm');
        $item = $request->get('item');
        $calendarId = $request->get('calendarId');
        $graphicId = $request->get('graphicId');
        $year = $request->get('year');
        $month = $request->get('month');
        $day = $request->get('day');
        $userId = $request->get('users');
        
        if ($appointmentTerm !== null && is_integer((int)$appointmentTerm) &&
            $item !== null && is_integer((int)$item) &&
            $calendarId !== null && is_integer((int)$calendarId) &&
            $graphicId !== null && is_integer((int)$graphicId) &&
            $year !== null && is_integer((int)$year) &&
            $month !== null && is_integer((int)$month) &&
            $day !== null && is_integer((int)$day) &&
            $userId !== null && is_integer((int)$userId))
        {            
            $explodedAppointmentTerm = explode(":", $appointmentTerm);
            
            if (count($explodedAppointmentTerm) == 2)
            {
                $appointmentTerm = htmlentities($appointmentTerm, ENT_QUOTES, "UTF-8");
                $item = htmlentities((int)$item, ENT_QUOTES, "UTF-8");
                $calendarId = htmlentities((int)$calendarId, ENT_QUOTES, "UTF-8");
                $graphicId = htmlentities((int)$graphicId, ENT_QUOTES, "UTF-8");
                $year = htmlentities((int)$year, ENT_QUOTES, "UTF-8");
                $month = htmlentities((int)$month, ENT_QUOTES, "UTF-8");
                $day = htmlentities((int)$day, ENT_QUOTES, "UTF-8");
                $userId = htmlentities((int)$userId, ENT_QUOTES, "UTF-8");
                
                $item = Item::where('id', $item)->first();
                
                if ($item !== null && $this->checkIfStillCanMakeAnAppointment($graphicId, $appointmentTerm, $item->minutes))
                {
                    $plusTime = "+" . $item->minutes . " minutes";
                    $endTime = date('G:i', strtotime($plusTime, strtotime($appointmentTerm)));
                    
                    $year = Year::where('year', $year)->where('calendar_id', $calendarId)->first();
                    $month = Month::where('month_number', $month)->where('year_id', $year->id)->first();
                    $day = Day::where('day_number', $day)->where('month_id', $month->id)->first();
                    
                    // store
                    $appointment = new Appointment();
                    $appointment->start_time = $appointmentTerm;
                    $appointment->end_time = $endTime;
                    $appointment->minutes = $item->minutes;
                    $appointment->graphic_id = $graphicId;
                    $appointment->day_id = $day->id;
                    $appointment->user_id = $userId;
                    $appointment->item_id = $item->id;
                    $appointment->save();
                    
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
                        'WorkerController@backendAppointmentShow', [
                            'id' => $appointment->id
                        ]
                    )->with('success', 'Wizyta została zarezerwowana. Informacja potwierdzająca została wysłana na maila!');
                    
                }
                
                return redirect()->action(
                    'WorkerController@backendCalendar', [
                        'calendarId' => $calendarId,
                        'year' => $year,
                        'month_number' => $month,
                        'day_number' => $day
                    ]
                )->with('error', 'Nie można zarezerwować wizyty! Być może jest już zajęta!');
            }
        }
        
        return redirect()->route('welcome');
    }
    
    /**
     * Checks if man still can make an appointment (code from create method)
     * 
     * @param type $graphicId
     * @param type $appointmentTerm
     * @param type $length
     * 
     * @return boolean|int
     */
    private function checkIfStillCanMakeAnAppointment($graphicId, $appointmentTerm, $itemLength)
    {
        $graphic = Graphic::find($graphicId);
        
        if ($graphic !== null)
        {
            $startTime = date('G:i', strtotime($graphic->start_time));
            $endTime = date('G:i', strtotime($graphic->end_time));
            $appointmentTerm = date('G:i', strtotime($appointmentTerm));

            if ((int)$appointmentTerm >= (int)$startTime && (int)$appointmentTerm < (int)$endTime)
            {
                $chosenAppointment = Appointment::where('graphic_id', $graphicId)->where('start_time', $appointmentTerm)->first();

                if ($chosenAppointment == null)
                {
                    $appointmentLength = [
                        0 => true
                    ];
                    $appointmentTermIncremented = $appointmentTerm;

                    for ($i = 0; $i < 2; $i++)
                    {
                        $appointmentTermIncremented = date('G:i', strtotime("+30 minutes", strtotime($appointmentTermIncremented)));

                        if ((int)$appointmentTermIncremented >= (int)$startTime && (int)$appointmentTermIncremented < (int)$endTime)
                        {
                            $nextAppointmentAvailable = Appointment::where('graphic_id', $graphicId)->where('start_time', $appointmentTermIncremented)->first();

                            if ($nextAppointmentAvailable === null)
                            {
                                $appointmentLength[] = true;
                            }
                            else
                            {
                                $appointmentLength[] = false;
                            }
                        }
                        else
                        {
                            $appointmentLength[] = false;
                        }
                    }
                    
                    $itemLength = $itemLength / 30;
                    
                    for ($i = 0; $i < $itemLength; $i++)
                    {
                        if ($appointmentLength[$i] == false)
                        {
                            return false;
                        }
                    }
                    
                    return true;
                }
            }
        }
        
        return false;
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
            $workUnits = ($graphicTime->total_time / 30);
            $startTime = date('G:i', strtotime($graphicTime->start_time));
            
            for ($i = 0; $i < $workUnits; $i++) 
            {
                $appointment = Appointment::where('day_id', $chosenDay->id)->where('start_time', $startTime)->with('user')->with('item')->first();

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
                        'appointment' => $appointment
                    ];
                    
                    $timeIncrementedByAppointmentMinutes = strtotime($appointment->end_time, strtotime($startTime));
                    $startTime = date('G:i', $timeIncrementedByAppointmentMinutes);
                }
                else
                {
                    $graphic[] = [
                        'time' => $startTime,
                        'appointment' => null
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
