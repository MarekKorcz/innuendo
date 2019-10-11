<?php

namespace App\Http\Controllers;

use App\Calendar;
use App\Category;
use App\Item;
use App\Graphic;
use App\User;
use App\TempUser;
use App\Year;
use App\Month;
use App\Day;
use App\Appointment;
use App\Property;
use App\ChosenProperty;
use App\Subscription;
use App\Purchase;
use App\Substart;
use App\Interval;
use Illuminate\Support\Collection;
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
     * @return type
     */
    public function graphicList()
    {
        $calendars = Calendar::where('employee_id', auth()->user()->id)->with('property')->get();
        
        if (count($calendars) > 0)
        {            
            if (count($calendars) == 1)
            {
                return redirect()->action(
                    'WorkerController@backendCalendar', [
                        'calendar_id' => $calendars->first()->id,
                        'year' => 0, 
                        'month_number' => 0, 
                        'day_number' => 0
                    ]
                );
                
            } else {
                
                return view('employee.backend_graphic')->with('calendars', $calendars);
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
    public function backendCalendar($calendar_id, $year = 0, $month_number = 0, $day_number = 0)
    {        
        $calendar = Calendar::where([
            'id' => $calendar_id,
            'isActive' => 1
        ])->first();
        
        if ($calendar !== null)
        {
            $currentDate = new \DateTime();
            
            if ($year == 0)
            {
                $year = Year::where([
                    'calendar_id' => $calendar->id,
                    'year' => $currentDate->format("Y")
                ])->first();
            }
            else if (is_numeric($year) && (int)$year > 0)
            {
                $year = Year::where([
                    'calendar_id' => $calendar->id,
                    'year' => $year
                ])->first();
            }
            
            if ($year !== null)
            {
                if ($month_number == 0)
                {
                    $month = Month::where([
                        'year_id' => $year->id,
                        'month_number' => $currentDate->format("n")
                    ])->first();
                }
                else if (is_numeric($month_number) && (int)$month_number > 0 || (int)$month_number <= 12)
                {
                    $month = Month::where([
                        'year_id' => $year->id,
                        'month_number' => $month_number
                    ])->first();
                }

                if ($month !== null)
                {
                    $days = Day::where('month_id', $month->id)->get();
                    
                    if ($days !== null)
                    {
                        $days = $this->formatDaysToUserCalendarForm($days, $month->days_in_month);

                        if ((int)$day_number === 0)
                        {
                            $currentDay = Day::where([
                                'month_id' => $month->id,
                                'day_number' => $currentDate->format("d")
                            ])->first();
                            
                        } else {
                            
                            $currentDay = Day::where([
                                'month_id' => $month->id,
                                'day_number' => $day_number
                            ])->first();
                        }

                        if ($currentDay !== null)
                        {
                            $graphicTime = Graphic::where('day_id', $currentDay->id)->first();
                            
                            $chosenDay = $currentDay;
                            $chosenDayDateTime = new \DateTime($year->year . "-" . $month->month_number . "-" . $chosenDay->day_number);
                            $graphic = $this->formatGraphicAndAppointments($graphicTime, $currentDay, $chosenDayDateTime);

                            $currentDay = $currentDay->day_number;
                            
                        } else {
                            
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
                        
                        $employee = User::where([
                            'isEmployee' => 1,
                            'id' => $calendar->employee_id
                        ])->first();
                        
                        $property = Property::where('id', $calendar->property_id)->first();

                        return view('employee.backend_calendar')->with([
                            'property' => $property,
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
                        
                    } else {
                        
                        $message = 'Brak otwartego grafiku na ten dzień';
                    }
                    
                } else {
                    
                    $message = 'Brak otwartego grafiku na ten miesiąc';
                }
                
            } else {
                
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
            
        } else {
            
            $message = 'Niepoprawny numer id kalendarza!';
        }
        
        return redirect()->action(
            'UserController@employeesList', []
        )->with('error', $message);
    }
    
    /**
     * Shows an appointment to employee or admin.
     * 
     * @param type $id
     * @return type
     */
    public function backendAppointmentShow($id)
    {
        if ($id !== null)
        {
            $appointment = Appointment::where('id', $id)->first();
            
            if ($appointment->user_id !== null)
            {
                $appointment = Appointment::where('id', $id)->with([
                    'item',
                    'user'
                ])->first();
                
            } else {
                
                $appointment = Appointment::where('id', $id)->with([
                    'item',
                    'tempUser'
                ])->first();
            }
            
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
                
                $isActivated = true;
                $subscriptionPurchaseId = null;
                $appointmentInterval = Interval::where('id', $appointment->interval_id)->first();
                
                if ($appointmentInterval !== null)
                {
                    if ($appointmentInterval->substart_id === null)
                    {
                        $bossMainInterval = Interval::where('id', $appointmentInterval->interval_id)->first();
                        
                        if ($bossMainInterval !== null)
                        {
                            $substart = Substart::where('id', $bossMainInterval->substart_id)->first();
                        
                            if ($substart !== null)
                            {
                                $subscriptionPurchaseId = $substart->purchase_id;
                                
                                if ($substart->isActive == 0)
                                {
                                    $isActivated = false;
                                }
                            }
                        }
                        
                    } elseif ($appointmentInterval->substart_id !== null) {
                                
                        $substart = Substart::where('id', $appointmentInterval->substart_id)->first();
                        
                        if ($substart !== null)
                        {
                            $subscriptionPurchaseId = $substart->purchase_id;
                            
                            if ($substart->isActive == 0)
                            {
                                $isActivated = false;
                            }
                        }
                    }
                }
                
                return view('employee.backend_appointment_show')->with([
                    'appointment' => $appointment,
                    'day' => $day->day_number,
                    'month' => $month->month,
                    'year' => $year->year,
                    'calendarId' => $calendar->id,
                    'employee' => $employee,
                    'property' => $property,
                    'statuses' => $statuses,
                    'isActivated' => $isActivated,
                    'subscriptionPurchaseId' => $subscriptionPurchaseId
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
        $user = User::where('id', $id)->first();
        
        if ($user !== null)
        {
            $appointments = Appointment::where('user_id', $user->id)->with('item')->orderBy('created_at', 'desc')->paginate(5);

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

                    $employee = $employee->name . " " . $employee->surname;
                    $appointment['employee'] = $employee;
                }

                return view('employee.backend_appointment_index')->with([
                    'appointments' => $appointments,
                    'user' => $user
                ]);
            }
        }
        
        return redirect()->route('welcome');
    }
    
    /**
     * Shows a list of appointments assigned to temporary user.
     * 
     * @param type $id
     * @return type
     */
    public function backendAppointmentIndexTempUser($id)
    {
        $appointments = Appointment::where('temp_user_id', $id)->with('item')->orderBy('created_at', 'desc')->paginate(5);
        
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
    
    /**
     * Shows backend users list
     */
    public function backendUsersIndex()
    {
        $users = new Collection();
        $employee = User::where([
            'id' => auth()->user()->id,
            'isEmployee' => 1
        ])->with('calendars')->first();
        
        if ($employee !== null && count($employee->calendars) > 0)
        {
            foreach ($employee->calendars as $calendar)
            {
                $property = Property::where('id', $calendar->property_id)->first();
                
                if ($property !== null && $property->boss() !== null)
                {
                    $boss = $property->boss();
                    $boss['property'] = $property;
                    
                    $users->push($boss);
                    
                    $bossWorkers = User::where('boss_id', $boss->id)->get();
                    
                    if (count($bossWorkers) > 0)
                    {
                        foreach ($bossWorkers as $bossWorker)
                        {
                            $bossWorker['property'] = $property;
                            
                            $users->push($bossWorker);
                        }
                    }
                }
            }
        }
            
        return view('employee.backend_users_index')->with([
            'users' => $users
        ]);
    }
    
    public function setAppointmentStatus(Request $request)
    {
        if ($request->request->all())
        {
            $appointmentId = htmlentities((int)$request->get('appointmentId'), ENT_QUOTES, "UTF-8");
            $appointment = Appointment::where('id', $appointmentId)->first();
            
            if ($appointment !== null)
            {
                $statusId = htmlentities((int)$request->get('statusId'), ENT_QUOTES, "UTF-8");
                
                if ($statusId && $statusId == 1)
                {
                    $appointment = Appointment::where('id', $appointmentId)->first();
                    
                    $appointmentInterval = Interval::where('id', $appointment->interval_id)->first();
                    $appointmentInterval->used_units = $appointmentInterval->used_units + 1;
                    
                    if ($appointmentInterval->interval_id != null)
                    {
                        $bossMainInterval = Interval::where('id', $appointmentInterval->interval_id)->first();
                        
                        if ($bossMainInterval !== null)
                        {
                            $bossMainInterval->available_units = $bossMainInterval->available_units - 1;
                        }
                        
                    } elseif ($appointmentInterval->interval_id == null) {
                        
                        $appointmentInterval->available_units = $appointmentInterval->available_units - 1;
                    }
                    
                    $appointmentInterval->save();
                    
                    $appointment->status = $statusId;
                    $appointment->save();

                    $data = [
                        'type'    => 'success',
                        'message' => 'Status wizyty został zmieniony!',
                        'status'  => config('appointment-status.' . $appointment->status)
                    ];

                    return new JsonResponse($data, 200, array(), true);
                }
                
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

                $graphic = Graphic::where('id', $graphicId)->first();
        
                if ($graphic !== null)
                {
                    $startTime = date('G:i', strtotime($graphic->start_time));
                    $endTime = date('G:i', strtotime($graphic->end_time));
                    $appointmentTerm = date('G:i', strtotime($appointmentTerm));

                    if ((int)$appointmentTerm >= (int)$startTime && (int)$appointmentTerm < (int)$endTime)
                    {
                        $chosenAppointment = Appointment::where([
                            'graphic_id' => $graphicId,
                            'start_time' => $appointmentTerm
                        ])->first();

                        if ($chosenAppointment == null)
                        {
                            $appointmentLength = 1;
                            $appointmentTermIncremented = $appointmentTerm;

                            for ($i = 0; $i < 2; $i++)
                            {
                                $appointmentTermIncremented = date('G:i', strtotime("+15 minutes", strtotime($appointmentTermIncremented)));

                                if ((int)$appointmentTermIncremented >= (int)$startTime && (int)$appointmentTermIncremented < (int)$endTime)
                                {
                                    $nextAppointmentAvailable = Appointment::where([
                                        'graphic_id' => $graphicId,
                                        'start_time' => $appointmentTermIncremented
                                    ])->first();

                                    if ($nextAppointmentAvailable === null)
                                    {
                                        $appointmentLength += 1;
                                        
                                    } else {
                                        
                                        break;
                                    }
                                    
                                } else {
                                    
                                    break;
                                }
                            }

                            $calendar = Calendar::where('id', $calendarId)->first();
                            
                            $possibleAppointmentLengthInMinutes = $appointmentLength * 15;
                            
                            $users = User::where([
                                'isAdmin' => null,
                                'isEmployee' => null
                            ])->pluck('name', 'id');
                            
                            return view('employee.backend_appointment_create')->with([
                                'appointmentTerm' => $appointmentTerm,
                                'calendarId' => $calendar->id,
                                'propertyId' => $calendar->property_id,
                                'graphicId' => $graphic->id,
                                'year' => $year,
                                'month' => $month,
                                'day' => $day,
                                'possibleAppointmentLengthInMinutes' => $possibleAppointmentLengthInMinutes,
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
    
    public function getUserFromDatabase(Request $request)
    {        
        if ($request->get('searchField'))
        {
            $users = User::where('name', 'like', $request->get('searchField') . '%')->orWhere('surname', 'like', $request->get('searchField') . '%')->where([
                'isEmployee' => null,
                'isAdmin' => null,
                'isBoss' => null
            ])->get();
            
            $data = [
                'type'    => 'success',
                'users'  => $users !== null ? $users : ""
            ];

            return new JsonResponse($data, 200, array(), true);
        }
        
        return new JsonResponse(array(
            'type'    => 'error',
            'message' => 'Pusty request'            
        ));
    }
    
    public function getUserItemsFromDatabase(Request $request)
    {        
        $userId = $request->get('userId');
        $propertyId = $request->get('propertyId');
        $possibleAppointmentLengthInMinutes = $request->get('possibleAppointmentLengthInMinutes');
            
        if ($userId !== null || $userId == 0 && $propertyId !== null && $possibleAppointmentLengthInMinutes !== null || $possibleAppointmentLengthInMinutes !== 0)
        {
            $user = User::where('id', $userId)->with('chosenProperties')->first();
            $property = Property::where('id', $propertyId)->first();
            $items = [];

            if ($property !== null && $possibleAppointmentLengthInMinutes !== null || $possibleAppointmentLengthInMinutes !== 0)
            {
                if ($userId !== 0 && $user !== null)
                {
                    if ($user->chosenProperties)
                    {
                        foreach ($user->chosenProperties as $chosenProperty)
                        {
                            if ($chosenProperty->property_id == $property->id)
                            {
                                $chosenProperty = ChosenProperty::where('id', $chosenProperty->id)->with('purchases')->first();

                                if ($chosenProperty->purchases)
                                {
                                    foreach ($chosenProperty->purchases as $purchase)
                                    {
                                        $purchase = Purchase::where('id', $purchase->id)->with('subscription')->first();
                                        $subscription = Subscription::where('id', $purchase->subscription->id)->with('items')->first();

                                        if ($subscription->items)
                                        {
                                            foreach ($subscription->items as $item)
                                            {
                                                if ($item->minutes <= $possibleAppointmentLengthInMinutes)
                                                {
                                                    $items[] = [
                                                        'item_id' => $item->id,
                                                        'item_name' => $item->name . ' - ' . $item->minutes . ' min - ' . $subscription->name,
                                                        'item_minutes' => $item->minutes,
                                                        'purchase_id' => $purchase->id
                                                    ];
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                
                $categoriesItems = Item::where('minutes', '<=', $possibleAppointmentLengthInMinutes)->get();

                if (count($categoriesItems) > 0)
                {
                    foreach ($categoriesItems as $categoriesItem)
                    {
                        $items[] = [
                            'item_id' => $categoriesItem->id,
                            'item_name' => $categoriesItem->name . ' - ' . $categoriesItem->minutes . ' min',
                            'item_minutes' => $categoriesItem->minutes,
                            'purchase_id' => 0
                        ];
                    }
                }
                
                $data = [
                    'type'      => 'success',
                    'user_name' => $user !== null ? $user->name : "Nowego użytkownika",
                    'items'     => $items
                ];

                return new JsonResponse($data, 200, array(), true);
            }
        }
        
        return new JsonResponse(array(
            'type'    => 'error',
            'message' => 'Pusty request'            
        ));
    }
    
    /**
     * Store a newly created resource in storage.
     * 
     * @param Request $request
     * @return type
     */
    public function appointmentStore(Request $request)
    {
        $appointmentTerm = htmlentities($request->get('appointmentTerm'), ENT_QUOTES, "UTF-8");
        $possibleAppointmentLengthInMinutes = htmlentities($request->get('possibleAppointmentLengthInMinutes'), ENT_QUOTES, "UTF-8");
        $purchaseId = htmlentities((int)$request->get('purchase_id'), ENT_QUOTES, "UTF-8");
        $itemId = htmlentities((int)$request->get('item_id'), ENT_QUOTES, "UTF-8");
        $propertyId = htmlentities((int)$request->get('property_id'), ENT_QUOTES, "UTF-8");
        $calendarId = htmlentities((int)$request->get('calendarId'), ENT_QUOTES, "UTF-8");
        $graphicId = htmlentities((int)$request->get('graphicId'), ENT_QUOTES, "UTF-8");
        $year = htmlentities((int)$request->get('year'), ENT_QUOTES, "UTF-8");
        $month = htmlentities((int)$request->get('month'), ENT_QUOTES, "UTF-8");
        $day = htmlentities((int)$request->get('day'), ENT_QUOTES, "UTF-8");
        
        $userId = null;
        
        if ($request->get('userId'))
        {
            $userId = htmlentities((int)$request->get('userId'), ENT_QUOTES, "UTF-8"); 
        }
        
        $name = null;
        
        if ($request->get('name'))
        {
            $name = htmlentities((int)$request->get('name'), ENT_QUOTES, "UTF-8"); 
        }
        
        $surname = null;
        
        if ($request->get('surname'))
        {
            $surname = htmlentities((int)$request->get('surname'), ENT_QUOTES, "UTF-8"); 
        }
        
        $email = null;
        
        if ($request->get('email'))
        {
            $email = htmlentities((int)$request->get('email'), ENT_QUOTES, "UTF-8"); 
        }
        
        $phone = null;
        
        if ($request->get('phone'))
        {
            $phone = htmlentities((int)$request->get('phone'), ENT_QUOTES, "UTF-8"); 
        }
        
        if ($appointmentTerm !== null && is_integer((int)$appointmentTerm) &&
            $possibleAppointmentLengthInMinutes !== null && is_integer((int)$possibleAppointmentLengthInMinutes) &&
            $purchaseId !== null && is_integer((int)$purchaseId) && $purchaseId >= 0 && 
            $itemId !== null && is_integer((int)$itemId) && $itemId > 0 && 
            $propertyId !== null && is_integer((int)$propertyId) && $propertyId > 0 && 
            $calendarId !== null && is_integer((int)$calendarId) &&
            $graphicId !== null && is_integer((int)$graphicId) &&
            $year !== null && is_integer((int)$year) &&
            $month !== null && is_integer((int)$month) &&
            $day !== null && is_integer((int)$day))
        {
            $item = Item::where('id', $itemId)->first();
            
            $year = Year::where([
                'year' => $year,
                'calendar_id' => $calendarId
            ])->first();
            
            if ($year !== null)
            {
                $month = Month::where([
                    'month_number' => $month,
                    'year_id' => $year->id
                ])->first();
                
            } else {
                
                return redirect()->route('welcome')->with('error', 'Nieprawidłowe dane');
            }
            
            if ($month !== null)
            {
                $day = Day::where([
                    'day_number' => $day,
                    'month_id' => $month->id
                ])->first();
                
            } else {
                
                return redirect()->route('welcome')->with('error', 'Nieprawidłowe dane');
            }
            
            if ($item == null || $year == null || $month == null || $day == null)
            {
                return redirect()->route('welcome')->with('error', 'Nieprawidłowe dane');
            }
            
            if ($userId !== null && is_integer((int)$userId) && $name == null && $surname == null && $email == null && $phone == null)
            {
                $explodedAppointmentTerm = explode(":", $appointmentTerm);

                if (count($explodedAppointmentTerm) == 2 && $this->checkIfStillCanMakeAnAppointment($graphicId, $appointmentTerm, $item->minutes))
                {
                    $plusTime = "+" . $item->minutes . " minutes";
                    $endTime = date('G:i', strtotime($plusTime, strtotime($appointmentTerm)));
                    
                    $graphic = Graphic::where([
                        'id' => $graphicId,
                        'day_id' => $day->id
                    ])->first();
                    
                    $user = User::where('id', $userId)->first();

                    $appointment = new Appointment();
                    $appointment->start_time = $appointmentTerm;
                    $appointment->end_time = $endTime;
                    $appointment->day_id = $day->id;
                    
                    if ($graphic !== null)
                    {
                        $appointment->graphic_id = $graphic->id;
                        
                    } else {
                        
                        return redirect()->action(
                            'WorkerController@backendCalendar', [
                                'calendarId' => $calendarId,
                                'year' => $year->year,
                                'month_number' => $month->month_number,
                                'day_number' => $day->day_number
                            ]
                        )->with('error', 'Podany grafik nie istnieje');
                    }
                    
                    if ($user !== null)
                    {
                        $appointment->user_id = $user->id;
                        
                    } else {
                        
                        return redirect()->action(
                            'WorkerController@backendCalendar', [
                                'calendarId' => $calendarId,
                                'year' => $year->year,
                                'month_number' => $month->month_number,
                                'day_number' => $day->day_number
                            ]
                        )->with('error', 'Podany użytkownik nie istnieje');
                    }
                    
                    if ($item->minutes <= $possibleAppointmentLengthInMinutes)
                    {
                        $appointment->item_id = $item->id;
                        $appointment->minutes = $item->minutes;
                        
                    } else {
                        
                        return redirect()->action(
                            'WorkerController@backendCalendar', [
                                'calendarId' => $calendarId,
                                'year' => $year->year,
                                'month_number' => $month->month_number,
                                'day_number' => $day->day_number
                            ]
                        )->with('error', 'Wizyta przekracza dostępny czas');
                    }
                    
                    if ($purchaseId > 0)
                    {
                        $purchase = Purchase::where('id', $purchaseId)->first();
                        
                        if ($purchase !== null)
                        {   
                            $subscription = Subscription::where('id', $purchase->subscription_id)->with('items')->first();
                            $isItemIdentical = false;

                            foreach ($subscription->items as $subscriptionItem)
                            {
                                if ($subscriptionItem->id == $item->id)
                                {
                                    $isItemIdentical = true;
                                    break;
                                }
                            }

                            if ($isItemIdentical)
                            {
                                $calendar = Calendar::where('id', $calendarId)->first();

                                $chosenProperty = ChosenProperty::where([
                                    'user_id' => $user->id,
                                    'property_id' => $calendar->property_id
                                ])->first();

                                if ($chosenProperty !== null)
                                {
                                    $purchase = Purchase::where([
                                        'chosen_property_id' => $chosenProperty->id,
                                        'subscription_id' => $subscription->id
                                    ])->first();

                                    if ($purchase !== null)
                                    {                                                        
                                        $chosenDay = (string)$day->day_number;
                                        $chosenDay = strlen($chosenDay) == 1 ? '0' . $chosenDay : $chosenDay;
                                        $chosenMonth = (string)$month->month_number;
                                        $chosenMonth = strlen($chosenMonth) == 1 ? '0' . $chosenMonth : $chosenMonth;

                                        $chosenDate = new \DateTime($year->year . '-' . $chosenMonth . '-' . $chosenDay);

                                        $purchaseIntervals = Interval::where('purchase_id', $purchase->id)->get();
                                        $currentInterval = new Collection();

                                        // looking for interval corresponding to the given date
                                        foreach ($purchaseIntervals as $purchaseInterval)
                                        {
                                            $startDate = new \DateTime($purchaseInterval->start_date);
                                            $endDate = new \DateTime($purchaseInterval->end_date);

                                            if ($startDate <= $chosenDate && $chosenDate <= $endDate)
                                            {
                                                $currentInterval->push($purchaseInterval);
                                                break;
                                            }
                                        }

                                        if (count($currentInterval) == 1)
                                        {
                                            $interval = $currentInterval->first();

                                            if ($interval->available_units > 0)
                                            {
                                                $appointment->purchase()->associate($purchase->id);

                                                $interval->available_units = ($interval->available_units - 1);
                                                $interval->save();

                                                $appointment->interval_id = $interval->id;

                                            } else {

                                                return redirect()->action(
                                                    'WorkerController@backendCalendar', [
                                                        'calendarId' => $calendarId,
                                                        'year' => $year->year,
                                                        'month_number' => $month->month_number,
                                                        'day_number' => $day->day_number
                                                    ]
                                                )->with('error', 'Liczba dostępnych wizyt subskrypcji dla danego miesiąca została przekroczona!');
                                            }
                                        }
                                    }
                                }
                            }
                            
                        } else {
                            
                            return redirect()->action(
                                'WorkerController@backendCalendar', [
                                    'calendarId' => $calendarId,
                                    'year' => $year->year,
                                    'month_number' => $month->month_number,
                                    'day_number' => $day->day_number
                                ]
                            )->with('error', 'Błędne dane wykupionej subskrypcji');
                        }
                    }
                        
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

            } else if ($name !== null && is_string($name) &&
                       $surname !== null && is_string($surname) &&
                       $email !== null && is_string($email) &&
                       $phone !== null && is_numeric($phone)) 
            {
                if ($this->checkIfStillCanMakeAnAppointment($graphicId, $appointmentTerm, $item->minutes))
                {
                    $tempUser = new TempUser();
                    $tempUser->name = $name;
                    $tempUser->surname = $surname;
                    $tempUser->email = $email;
                    $tempUser->phone_number = $phone;
                    $tempUser->save();

                    if ($tempUser !== null)
                    {
                        $plusTime = "+" . $item->minutes . " minutes";
                        $endTime = date('G:i', strtotime($plusTime, strtotime($appointmentTerm)));

                        $appointment = new Appointment();
                        $appointment->start_time = $appointmentTerm;
                        $appointment->end_time = $endTime;
                        $appointment->minutes = $item->minutes;
                        $appointment->graphic_id = $graphicId;
                        $appointment->day_id = $day->id;
                        $appointment->temp_user_id = $tempUser->id;
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
        
        return redirect()->route('welcome')->with('error', 'Nieprawidłowe dane');
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function appointmentEdit($id)
    {
        $appointment = Appointment::where('id', $id)->with('item')->first();
        
        if ($appointment !== null)
        {
            $category = Category::where('id', $appointment->item->category_id)->first();
            
            $items = [];

            if ($category !== null)
            {
                $itemsObject = Item::where('category_id', $category->id)->get();

                foreach ($itemsObject as $item)
                {
                    $items[] = [
                        'key' => $item->id,
                        'value' => $item->name . " - " . $item->minutes . " min - " . $item->price . " zł",
                        'isActive' => $appointment->item->id == $item->id ? true : false
                    ];
                }
            }

            $statuses = [];

            for($i = 0; $i < 4; $i++)
            {
                $statuses[] = [
                    'key' => $i,
                    'value' => config('appointment-status.' . $i),
                    'isActive' => $appointment->status == $i ? true : false
                ];
            }

            return view('employee.backend_appointment_edit')->with([
                'appointment' => $appointment,
                'items' => $items,
                'statuses' => $statuses
            ]);
        }
        
        return redirect()->route('welcome');
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @return Response
     */
    public function appointmentUpdate(Request $request)
    {        
        $startTime = $request->get('start_time');
        $endTime = $request->get('end_time');
        $item = $request->get('item');
        $status = $request->get('status');
        $appointmentId = $request->get('appointmentId');
        
        if ($startTime !== null &&
            $endTime !== null &&
            $item !== null && is_integer((int)$item) &&
            $status !== null && is_integer((int)$status) &&
            $appointmentId !== null && is_integer((int)$appointmentId))
        {
            $appointment = Appointment::where('id', $appointmentId)->first();
            $graphic = Graphic::where('id', $appointment->graphic_id)->first();
        
            if ($graphic !== null)
            {
                $appointmentStartTime = date('G:i', strtotime($startTime));
                $appointmentEndTime = date('G:i', strtotime($endTime));

                if ((int)$appointmentStartTime < (int)$appointmentEndTime)
                {
                    $explodedAppointmentStartTime = explode(":", $startTime);
                    $explodedAppointmentEndTime = explode(":", $endTime);

                    for ($i = 0; $i < count($explodedAppointmentStartTime); $i++)
                    {
                        if (!is_numeric($explodedAppointmentStartTime[$i]))
                        {
                            return redirect()->route('welcome');
                        }
                    }

                    for ($i = 0; $i < count($explodedAppointmentEndTime); $i++)
                    {
                        if (!is_numeric($explodedAppointmentEndTime[$i]))
                        {
                            return redirect()->route('welcome');
                        }
                    }

                    $startTime = htmlentities($startTime, ENT_QUOTES, "UTF-8");
                    $endTime = htmlentities($endTime, ENT_QUOTES, "UTF-8");
                    $item = htmlentities((int)$item, ENT_QUOTES, "UTF-8");
                    $status = htmlentities((int)$status, ENT_QUOTES, "UTF-8");
                    $appointmentId = htmlentities((int)$appointmentId, ENT_QUOTES, "UTF-8");
                    
                    $start = new \DateTime($startTime);
                    $end = new \DateTime($endTime);
                    
                    $difference = $start->diff($end);
                    
                    $minutes = $difference->i;
                    $hours= $difference->h;
                    
                    $totalMinutes = 0;
                    $totalMinutes += $minutes != 0 ? $minutes : 0;
                    $totalMinutes += $hours != 0 ? $hours * 60 : 0;
                    
                    if ($this->checkIfStillCanMakeAnAppointment($graphic->id, $startTime, $totalMinutes))
                    {
                        $item = Item::where('id', $item)->first();
                        
                        if ($totalMinutes == $item->minutes)
                        {
                            // store
                            $appointment->start_time = $startTime;
                            $appointment->end_time = $endTime;

                            if ($appointment->item_id != $item->id)
                            {
                                $appointment->item_id = $item->id;
                                $appointment->minutes = $item->minutes;
                            }

                            $appointment->status = $status;
                            $appointment->save();
                            
                            $messageType = 'success';
                            $message = 'Wizyta została zaktualizowana!';
                            
                        } else {
                            
                            $messageType = 'error';
                            $message = 'Wybrany zakres czasu niezgodny z długością wybranego zabiegu';
                        }
                        
                    } else {
                        
                        $messageType = 'error';
                        $message = 'Wizyta koliduję z inną wizytą';
                    }
                    
                } else {
                    
                    $messageType = 'error';
                    $message = 'Godzina rozpoczęcia jest większa niż godzina zakończenia!';
                }
                
                return redirect('/employee/backend-appointment/show/' . $appointment->id)->with($messageType, $message);
                
            } else {
            
                $message = 'Grafik nie istnieje';
            }
            
        } else {
            
            $message = 'Nieprawidłowe dane';
        } 
            
        return redirect()->route('welcome')->with('error', $message);
    }
    
    public function activateSubscription($purchaseId, $appointmentId)
    {
        $purchase = Purchase::where('id', $purchaseId)->with('subscription')->first();
        
        if ($purchase !== null)
        {
            $substart = Substart::where('id', $purchase->substart_id)->first();
                
            if ($substart->isActive == 0)
            {
                $substartPurchaseIntervalsWithTheirAppointments = [];
                
                if ($substart->boss_id !== null)
                {
                    $substartPurchases = Purchase::where('substart_id', $substart->id)->orderBy('id', 'asc')->get();
                   
                    if (count($substartPurchases) > 0)
                    {
                        foreach ($substartPurchases as $substartPurchase)
                        {
                            $interval = Interval::where('purchase_id', $substartPurchase->id)->first();
                            
                            if ($interval !== null)
                            {
                                $intervalAppointments = Appointment::where([
                                    'interval_id' => $interval->id,
                                    'purchase_id' => $substartPurchase->id
                                ])->get();
                                
                                $appointments = [];
                                
                                if (count($intervalAppointments) > 0)
                                {
                                    foreach ($intervalAppointments as $intervalAppointment)
                                    {
                                        $appointments[] = $intervalAppointment;
                                    }
                                }
                                
                                $substartPurchaseIntervalsWithTheirAppointments[] = [
                                    'interval' => $interval,
                                    'appointments' => $appointments
                                ];
                            }
                        }                    
                    }
                    
                } else if ($substart->user_id !== null) {
                    
                    $interval = Interval::where('purchase_id', $purchase->id)->first();
                    
                    if ($interval !== null)
                    {
                        $intervalAppointments = Appointment::where([
                            'interval_id' => $interval->id,
                            'purchase_id' => $purchase->id
                        ])->get();

                        $appointments = [];

                        if (count($intervalAppointments) > 0)
                        {
                            foreach ($intervalAppointments as $intervalAppointment)
                            {
                                $appointments[] = $intervalAppointment;
                            }
                        }

                        $substartPurchaseIntervalsWithTheirAppointments[] = [
                            'interval' => $interval,
                            'appointments' => $appointments
                        ];
                    }
                }
                                
                if (count($substartPurchaseIntervalsWithTheirAppointments) > 0)
                {
                    $subscription = $purchase->subscription;
                    $newIntervals = [];
                    
                    $subscriptionStartDate = date('Y-m-d');
                    $startDateIncrementedBySubscriptionLength = date('Y-m-d', strtotime("+" . $subscription->duration . " month", strtotime(date('Y-m-d'))));
                    $subscriptionEndDate = date('Y-m-d', strtotime("-1 day", strtotime($startDateIncrementedBySubscriptionLength)));
                    
                    $substart->start_date = $subscriptionStartDate;
                    $substart->end_date = $subscriptionEndDate;
                    $substart->isActive = 1;
                    $substart->save();
                    
                    foreach ($substartPurchaseIntervalsWithTheirAppointments as $intervalWithAppointments)
                    {                              
                        $startDate = $substart->start_date;
                        
                        if ($intervalWithAppointments['interval']->substart_id === null)
                        {
                            for ($i = 1; $i <= $subscription->duration; $i++)
                            {
                                $bossIntervalStartDate = $startDate;
                                
                                $interval = new Interval();
                                $interval->start_date = $startDate;
                                $startDate = date('Y-m-d', strtotime("+1 month", strtotime($startDate)));
                                $endDate = date('Y-m-d', strtotime("-1 day", strtotime($startDate)));
                                $interval->end_date = $endDate;
                                $interval->purchase_id = $intervalWithAppointments['interval']->purchase_id;
                                
                                $bossMainInterval = Interval::where([
                                    'purchase_id' => $purchase->id,
                                    'substart_id' => $substart->id,
                                    'interval_id' => null,
                                    'start_date' => is_object($bossIntervalStartDate) ? $bossIntervalStartDate->format('Y-m-d') : $bossIntervalStartDate,
                                    'end_date' => $endDate
                                ])->first();
                                
                                if ($bossMainInterval !== null)
                                {
                                    $interval->interval_id = $bossMainInterval->id;
                                }
                                
                                $interval->save();

                                if ($interval !== null)
                                {
                                    foreach ($intervalWithAppointments['appointments'] as $appointment)
                                    {
                                        $day = Day::where('id', $appointment->day_id)->first();
                                        $month = Month::where('id', $day->month_id)->first();
                                        $year = Year::where('id', $month->year_id)->first();

                                        $monthNumber = strlen($month->month_number) == 1 ? '0' . $month->month_number : $month->month_number;
                                        $dayNumber = strlen($day->day_number) == 1 ? '0' . $day->day_number : $day->day_number;

                                        $dateString = $year->year . '-' . $monthNumber . '-' . $dayNumber;
                                        $appointmentDate = new \DateTime($dateString);
                                        
                                        if ($appointmentDate >= $interval->start_date && $appointmentDate <= $interval->end_date)
                                        {
                                            $appointment->interval_id = $interval->id;
                                            $appointment->save();
                                            
                                            if ($interval->interval_id !== null)
                                            {
                                                $bossMainInterval = Interval::where([
                                                    'id' => $interval->interval_id
                                                ])->first();

                                                if ($bossMainInterval !== null)
                                                {
                                                    $bossMainInterval->available_units = ($bossMainInterval->available_units - 1);
                                                    $bossMainInterval->save();
                                                }
                                            }
                                        }
                                    }

                                    $intervalWithAppointments['interval']->delete();
                                }
                                
                                $bossIntervalStartDate = date('Y-m-d', strtotime("+1 month", strtotime($bossIntervalStartDate)));
                                $bossIntervalStartDate = date('Y-m-d', strtotime("-1 day", strtotime($bossIntervalStartDate)));
                            }
                            
                        } elseif ($intervalWithAppointments['interval']->substart_id !== null) {
                            
                            for ($i = 1; $i <= $subscription->duration; $i++)
                            {
                                $interval = new Interval();
                                $interval->available_units = $subscription->quantity * $subscription->duration;
                                $interval->start_date = $startDate;
                                $startDate = date('Y-m-d', strtotime("+1 month", strtotime($startDate)));
                                $endDate = date('Y-m-d', strtotime("-1 day", strtotime($startDate)));
                                $interval->end_date = $endDate;
                                $interval->substart_id = $intervalWithAppointments['interval']->substart_id;
                                $interval->purchase_id = $intervalWithAppointments['interval']->purchase_id;
                                $interval->save();

                                if ($interval !== null)
                                {
                                    foreach ($intervalWithAppointments['appointments'] as $appointment)
                                    {
                                        $day = Day::where('id', $appointment->day_id)->first();
                                        $month = Month::where('id', $day->month_id)->first();
                                        $year = Year::where('id', $month->year_id)->first();

                                        $monthNumber = strlen($month->month_number) == 1 ? '0' . $month->month_number : $month->month_number;
                                        $dayNumber = strlen($day->day_number) == 1 ? '0' . $day->day_number : $day->day_number;

                                        $dateString = $year->year . '-' . $monthNumber . '-' . $dayNumber;
                                        $appointmentDate = new \DateTime($dateString);
                                        
                                        if ($appointmentDate >= $interval->start_date && $appointmentDate <= $interval->end_date)
                                        {
                                            $appointment->interval_id = $interval->id;
                                            $appointment->save();

                                            $interval->available_units = ($interval->available_units - 1);
                                            $interval->save();
                                        }
                                    }

                                    $intervalWithAppointments['interval']->delete();
                                }
                            }
                        }
                    }
                    
                    // >> clear all past appointments to the time subscription become activated
                    $allAppointments = Appointment::all();
                    
                    $allAppointments->map(function ($item) {

                        $appointmentInterval = Interval::where('id', $item->interval_id)->first();

                        if ($appointmentInterval == null)
                        {
                            $item->delete();

                        } else {

                            return $item;
                        }
                    });
                    // <<
                }
            }
            
            return redirect()->action(
                'WorkerController@backendAppointmentShow', [
                    'id' => $appointmentId
                ]
            );
        }
        
        return redirect()->route('welcome')->with('error', 'Coś poszło nie tak');
    }
    
    /**
     * Checks if man still can make an appointment (code from create method)
     * 
     * @param type $graphicId
     * @param type $appointmentTerm
     * @param type $itemLength
     * 
     * @return boolean|int
     */
    private function checkIfStillCanMakeAnAppointment($graphicId, $appointmentTerm, $itemLength)
    {
        $graphic = Graphic::where('id', $graphicId)->first();
        
        if ($graphic !== null)
        {
            $startTime = date('G:i', strtotime($graphic->start_time));
            $endTime = date('G:i', strtotime($graphic->end_time));
            $appointmentTerm = date('G:i', strtotime($appointmentTerm));

            if ((int)$appointmentTerm >= (int)$startTime && (int)$appointmentTerm < (int)$endTime)
            {
                $chosenAppointment = Appointment::where([
                    'graphic_id' => $graphicId,
                    'start_time' => $appointmentTerm
                ])->first();

                if ($chosenAppointment == null)
                {
                    $appointmentLength = [
                        0 => true
                    ];
                    $appointmentTermIncremented = $appointmentTerm;

                    for ($i = 0; $i < 5; $i++)
                    {
                        $appointmentTermIncremented = date('G:i', strtotime("+15 minutes", strtotime($appointmentTermIncremented)));

                        if ((int)$appointmentTermIncremented >= (int)$startTime && (int)$appointmentTermIncremented < (int)$endTime)
                        {
                            $nextAppointmentAvailable = Appointment::where([
                                'graphic_id' => $graphicId,
                                'start_time' => $appointmentTermIncremented
                            ])->first();

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
                    
                    $itemLength = $itemLength / 15;
                    
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
            $workUnits = ($graphicTime->total_time / 15);
            $startTime = date('G:i', strtotime($graphicTime->start_time));
            
            for ($i = 0; $i < $workUnits; $i++) 
            {
                $appointment = Appointment::where([
                    'day_id' => $chosenDay->id,
                    'start_time' => $startTime
                ])->first();

                if ($appointment !== null)
                {
                    if ($appointment->user_id !== null)
                    {
                        $appointment = Appointment::where([
                            'day_id' => $chosenDay->id,
                            'start_time' => $startTime
                        ])->with([
                            'user',
                            'item'
                        ])->first();

                    } else {

                        $appointment = Appointment::where([
                            'day_id' => $chosenDay->id,
                            'start_time' => $startTime
                        ])->with([
                            'tempUser',
                            'item'
                        ])->first();
                    }
                    
                    $limit = $appointment->minutes / 15;
                    
                    if ($limit > 1)
                    {
                        $time = array($startTime);

                        for ($j = 1; $j < $limit; $j++)
                        {
                            $time[] = date('G:i', strtotime("+15 minutes", strtotime($time[count($time) - 1])));
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
                    
                    $timeIncrementedBy15Minutes = strtotime("+15 minutes", strtotime($startTime));
                    $startTime = date('G:i', $timeIncrementedBy15Minutes);
                }
            }            
        }
        
        return $graphic;
    }
    
    private function checkIfPreviewMonthIsAvailable($calendar, $year, $month)
    {
        if ($month->month_number == 1)
        {
            $year = Year::where([
                'calendar_id' => $calendar->id,
                'year' => ($year->year - 1)
            ])->first();
            
            if ($year === null)
            {
                return false;
            }
            else
            {
                $month = Month::where([
                    'year_id' => $year->id,
                    'month_number' => 12
                ])->first();
                
                if ($month === null) 
                {
                    return false;
                }
            }
        }
        else
        {
            $month = Month::where([
                'year_id' => $year->id,
                'month_number' => ($month->month_number - 1)
            ])->first();
                
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
            $year = Year::where([
                'calendar_id' => $calendar->id,
                'year' => ($year->year + 1)
            ])->first();
            
            if ($year === null)
            {
                return false;
            }
            else
            {
                $month = Month::where([
                    'year_id' => $year->id,
                    'month_number' => 1
                ])->first();
                
                if ($month === null) 
                {
                    return false;
                }
            }
        }
        else
        {
            $month = Month::where([
                'year_id' => $year->id,
                'month_number' => ($month->month_number + 1)
            ])->first();
                
            if ($month === null) 
            {
                return false;
            }
        }
        
        return true;
    }
}
