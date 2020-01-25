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
use App\ChosenProperty;
use App\Subscription;
use App\Purchase;
use App\Substart;
use App\Interval;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Redirect;

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
        $employee = User::where('id', auth()->user()->id)->with('graphics')->first();
                
        if ($employee !== null)
        {        
            $employeeGraphics = $employee->graphics;
            $employeeGraphicProperties = new Collection();
            
            if (count($employeeGraphics) == 1)
            {
                return redirect()->action(
                    'WorkerController@backendCalendar', [
                        'property_id' => $employeeGraphics->first()->property_id,
                        'year' => 0, 
                        'month_number' => 0, 
                        'day_number' => 0
                    ]
                );
                
            } else if (count($employeeGraphics) > 1) {
                
                if (count($employeeGraphics) > 0)
                {
                    $employeeGraphics->each(function($item) use ($employeeGraphicProperties) 
                    {
                        if (!$employeeGraphicProperties->contains('id', $item->property->id))
                        {
                            $employeeGraphicProperties->push($item->property);
                        }
                    });
                }
                
                return view('employee.backend_graphic')->with('properties', $employeeGraphicProperties);
            }
            
            if (substr($employee->name, -1) == "a")
            {
                $message = \Lang::get('common.no_schedule_yet_female');

            } else {

                $message = \Lang::get('common.no_schedule_yet_male');
            }
            
            return redirect()->route('home')->with('success', $message);
        }
        
        return redirect()->route('welcome');
    }

    /**
     * Shows calendar that belongs to employee.
     * 
     * @param integer $property_id
     * @param integer $year
     * @param integer $month_number
     * @param integer $day_number
     * 
     * @return type
     * @throws Exception
     */
    public function backendCalendar($property_id, $year = 0, $month_number = 0, $day_number = 0)
    {      
        $property = Property::where([
            'id' => $property_id
        ])->with('boss')->first();
        
        if ($property !== null)
        {
            $currentDate = new \DateTime();
            
            if ($year == 0)
            {
                $year = Year::where([
                    'property_id' => $property->id,
                    'year' => $currentDate->format("Y")
                ])->first();
                
            } else if (is_numeric($year) && (int)$year > 0) {
                
                $year = Year::where([
                    'property_id' => $property->id,
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
                    
                } else if (is_numeric($month_number) && (int)$month_number > 0 || (int)$month_number <= 12) {
                    
                    $month = Month::where([
                        'year_id' => $year->id,
                        'month_number' => $month_number
                    ])->first();
                }

                if ($month !== null)
                {
                    $days = Day::where('month_id', $month->id)->get();
                    
                    if (count($days) > 0)
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
                            $graphicTimes = Graphic::where('day_id', $currentDay->id)->with('employee')->get();
                            
                            $chosenDay = $currentDay;
                            $graphic = $this->formatGraphicAndAppointments($graphicTime, $currentDay);
                            
                        } else {
                            
                            $graphic = [];
                            $graphicTime = null;
                            $graphicTimes = null;
                        }
                        
                        $availablePreviousMonth = false;

                        if ($this->checkIfPreviewMonthIsAvailable($property, $year, $month))
                        {
                            $availablePreviousMonth = true;
                        }
                        
                        $availableNextMonth = false;

                        if ($this->checkIfNextMonthIsAvailable($property, $year, $month))
                        {
                            $availableNextMonth = true;
                        }
                        
                        return view('employee.backend_calendar')->with([
                            'property' => $property,
                            'availablePreviousMonth' => $availablePreviousMonth,
                            'availableNextMonth' => $availableNextMonth,
                            'year' => $year,
                            'month' => $month,
                            'days' => $days,
                            'current_day' => is_object($currentDay) ? $currentDay->day_number : 0,
                            'current_day_id' => is_object($currentDay) ? $currentDay->id : 0,
                            'graphic' => $graphic,
                            'graphic_id' => $graphicTime !== null ? $graphicTime->id : null,
                            'graphicTimesEntites' => $graphicTimes,
                            'employees' => User::where('isEmployee', 1)->get()
                        ]);
                        
                    } else {
                        
                        $message = 'Brak otwartego grafiku na ten dzień';
                    }
                    
                } else {
                    
                    $message = 'Brak otwartego grafiku na ten miesiąc';
                }
                
            } else {
                
                $message = 'Brak otwartego kalendarza na ten rok';
            }
            
        } else {
            
            $message = 'Niepoprawny numer id';
        }
        
        return redirect()->route('welcome')->with('error', $message);
    }
    
    public function getEmployeeGraphic(Request $request)
    {
        if ($request->get('graphicId') && $request->get('currentDayId'))
        {
            $graphic = Graphic::where('id', $request->get('graphicId'))->first();
            $day = Day::where('id', $request->get('currentDayId'))->first();
            
            if ($graphic !== null && $day !== null)
            {
                $graphicArray = $this->formatGraphicAndAppointments($graphic, $day);
                
                foreach ($graphicArray as $key => $graphArr)
                {
                    if ($graphArr['appointment'] !== null)
                    {
                        $graphicArray[$key]['appointmentId'] = $graphArr['appointment']->id;
                        $graphicArray[$key]['appointmentUserName'] = $graphArr['appointment']->user->name . " " . $graphArr['appointment']->user->surname;
                        $graphicArray[$key]['appointmentHref'] = route('backendAppointmentShow', [
                            'id' => $graphArr['appointment']->id
                        ]);
                        
                        unset($graphicArray[$key]['appointment']);
                    }
                }
                
                return new JsonResponse([
                    'type' => 'success',
                    'graphic' => $graphicArray,
                    'availableDescription' => \Lang::get('common.available'),
                    'clickToMakeReservationDescription' => \Lang::get('common.click_to_make_reservation')
                ], 200, array(), true);
            }
        }
        
        return new JsonResponse(array(
            'type'    => 'error'        
        ));
    }
    
    /**
     * Shows an appointment to employee or admin.
     * 
     * @param type $id
     * @return type
     */
    public function backendAppointmentShow($id)
    {
        $appointment = Appointment::where('id', $id)->with([
            'day.month.year.property',
            'item',
            'user',
            'graphic.employee'
        ])->first();

        if ($appointment !== null)
        {
            $day = $appointment->day;
            $month = $appointment->day->month;
            $year = $appointment->day->month->year;
            $property = $appointment->day->month->year->property;
            $employee = $appointment->graphic->employee;

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
                'month' => $month,
                'year' => $year->year,
                'property' => $property,
                'employee' => $employee,
                'property' => $property,
                'statuses' => $statuses
            ]);
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
            $appointments = Appointment::where('user_id', $user->id)->with([
                'day.month.year.property',
                'graphic.employee'
            ])->orderBy('created_at', 'desc')->paginate(5);

            if (count($appointments) > 0)
            {
                foreach ($appointments as $appointment)
                {
                    $appointment['day'] = $appointment->day;
                    $appointment['month'] = $appointment->day->month;
                    $appointment['year'] = $appointment->day->month->year;
                    $appointment['property'] = $appointment->day->month->year->property;
                    
                    $appointment['date'] = $appointment['day']->day_number. ' ' . $appointment['month']->month . ' ' . $appointment['year']->year;

                    $employee = $appointment->graphic->employee;
                    $appointment['employee_name'] = $employee->name . " " . $employee->surname;
                    $appointment['employee_slug'] = $employee->slug;
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
     * Shows backend users list
     */
    public function backendUsersIndex()
    {
        $users = User::where('isBoss', '!=', null)->orWhere('boss_id', '!=', null)->get();
        
        if (count($users) > 0)
        {
            foreach ($users as $user)
            {
                $user['boss'] = $user->getBoss();
            }
        }
        
        return view('employee.backend_users_index')->with([
            'users' => $users
        ]);
    }
    
    public function setAppointmentStatus(Request $request)
    {
        $appointment = Appointment::where('id', $request->get('appointmentId'))->first();

        if ($appointment !== null)
        {
            $appointment->status = htmlentities((int)$request->get('statusId'), ENT_QUOTES, "UTF-8");
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
            $request->graphicId)
        {
            session([
                'appointmentTerm' => $request->appointmentTerm,
                'graphicId' => $request->graphicId
            ]);
            
            if (auth()->user()->isEmployee)
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
        if ($request->session()->get('appointmentTerm') !== null &&
            $request->session()->get('graphicId') !== null && is_integer((int)$request->session()->get('graphicId')))
        {           
            $explodedAppointmentTerm = explode(":", $request->session()->get('appointmentTerm'));
            
            if (count($explodedAppointmentTerm) == 2)
            {
                $appointmentTerm = htmlentities($request->session()->get('appointmentTerm'), ENT_QUOTES, "UTF-8");
                $graphicId = htmlentities((int)$request->session()->get('graphicId'), ENT_QUOTES, "UTF-8");
                
                $request->session()->forget('appointmentTerm');
                $request->session()->forget('graphicId');

                $graphic = Graphic::where('id', $graphicId)->with('day.month.year.property')->first();
        
                if ($graphic !== null)
                {
                    $startTime = date('G:i', strtotime($graphic->start_time));
                    $endTime = date('G:i', strtotime($graphic->end_time));
                    $appointmentTerm = date('G:i', strtotime($appointmentTerm));

                    if ($appointmentTerm >= $startTime && $appointmentTerm < $endTime)
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
                                $appointmentTermIncremented = date('G:i', strtotime("+20 minutes", strtotime($appointmentTermIncremented)));

                                if ($appointmentTermIncremented >= $startTime && $appointmentTermIncremented < $endTime)
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
                            
                            $users = User::where([
                                'isAdmin' => null,
                                'isEmployee' => null
                            ])->pluck('name', 'id');
                            
                            return view('employee.backend_appointment_create')->with([
                                'appointmentTerm' => $appointmentTerm,
                                'property' => $graphic->day->month->year->property,
                                'graphic' => $graphic,
                                'year' => $graphic->day->month->year,
                                'month' => $graphic->day->month,
                                'day' => $graphic->day,
                                'possibleAppointmentLengthInMinutes' => $appointmentLength * 20,
                                'users' => $users
                            ]);
                            
                        } else {
                            
                            $message = 'Wizyta jest już zajęta';
                        }
                        
                    } else {
                        
                        $message = 'Niepoprawny termin wizyty';
                    }
                    
                } else {
                    
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
        $message = 'Pusty request';
        
        if ($request->get('searchField') && $request->get('propertyId'))
        {          
            $searchField = $request->get('searchField');
            $property = Property::where('id', $request->get('propertyId'))->with('boss')->first();

            if ($property !== null)
            {
                $usersFromDBWithNameRelatedToSearchQuery = User::where(function ($query) use ($searchField) {
                    $query->where('name', 'like', $searchField . '%')
                            ->where([
                                'isEmployee' => null,
                                'isAdmin' => null
                            ]);
                })->orWhere(function ($query) use ($searchField) {
                    $query->where('surname', 'like', $searchField . '%')
                        ->where([
                            'isEmployee' => null,
                            'isAdmin' => null
                        ]);
                })->get();

                if (count($usersFromDBWithNameRelatedToSearchQuery) > 0)
                {
                    $users = new Collection();

                    foreach ($usersFromDBWithNameRelatedToSearchQuery as $user)
                    {
                        if ($user->boss_id !== null && $user->boss_id == $property->boss->id || 
                            $user->isBoss !== null && $user->id == $property->boss->id)
                        {
                            $users->push($user);
                        }
                    }

                    $data = [
                        'type'    => 'success',
                        'users'  => $users
                    ];

                    return new JsonResponse($data, 200, array(), true);
                }

                $message = 'Fraza nie zwrocila zadnego wyniku';
            }
        }
        
        return new JsonResponse(array(
            'type'    => 'error',
            'message' => $message           
        ));
    }
    
    public function getItemsFromDatabase(Request $request)
    {        
        $possibleAppointmentLengthInMinutes = $request->get('possibleAppointmentLengthInMinutes');
            
        if ($possibleAppointmentLengthInMinutes !== null || $possibleAppointmentLengthInMinutes !== 0)
        {
            $itemsCollection = Item::where('minutes', '<=', $possibleAppointmentLengthInMinutes)->get();
            $items = [];

            if (count($itemsCollection) > 0)
            {
                foreach ($itemsCollection as $item)
                {
                    $items[] = [
                        'item_id' => $item->id,
                        'item_name' => $item->name . ' - ' . $item->minutes . ' min',
                        'item_minutes' => $item->minutes,
                        'purchase_id' => 0
                    ];
                }
            }

            $user = User::where('id', $request->get('userId'))->first();

            $data = [
                'type'      => 'success',
                'user_name' => $user !== null ? $user->name . " " . $user->surname : "Nowego użytkownika",
                'items'     => $items
            ];

            return new JsonResponse($data, 200, array(), true);
        }
        
        return new JsonResponse(array(
            'type'    => 'error',
            'message' => 'Pusty request'            
        ));
    }
    
    /**
     * Store a newly created resource in storage.
     * 
     * @return type
     */
    public function appointmentStore()
    {
        $rules = array(
            'appointmentTerm' => 'required',
            'userId' => 'required',
            'item_id' => 'required',
            'graphicId' => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return Redirect::to('property/create')
                ->withErrors($validator);
        } else {
        
            $graphic = Graphic::where('id', Input::get('graphicId'))->with([
                'day.month.year',
                'property'
            ])->first();
            $item = Item::where('id', Input::get('item_id'))->first();
            $possibleAppointmentLengthInMinutes = Input::get('possibleAppointmentLengthInMinutes');
            $appointmentTerm = Input::get('appointmentTerm');

            if ($graphic !== null && $item !== null && $possibleAppointmentLengthInMinutes !== null && $appointmentTerm !== null)
            {
                $explodedAppointmentTerm = explode(":", $appointmentTerm);

                if (count($explodedAppointmentTerm) == 2 && $this->checkIfStillCanMakeAnAppointment($graphic->id, $appointmentTerm, $item->minutes))
                {
                    $plusTime = "+" . $item->minutes . " minutes";
                    $endTime = date('G:i', strtotime($plusTime, strtotime($appointmentTerm)));

                    $user = User::where('id', Input::get('userId'))->first();

                    $appointment = new Appointment();
                    $appointment->start_time = $appointmentTerm;
                    $appointment->end_time = $endTime;
                    $appointment->graphic_id = $graphic->id;
                    $appointment->day_id = $graphic->day->id;
                    $appointment->user_id = $user->id;
                    $appointment->item_id = $item->id;
                    $appointment->minutes = $item->minutes;
                    $appointment->save();

                    /**
                     * 
                     * 
                     * 
                     * todo: email sanding
                     * 
                     * 
                     * 
                     * 
                     */
                    
                    return redirect()->action(
                        'WorkerController@backendCalendar', [
                            'propertyId' => $graphic->property->id,
                            'year' => $graphic->day->month->year->year,
                            'month_number' => $graphic->day->month->month_number,
                            'day_number' => $graphic->day->day_number
                        ]
                    )->with('success', 'Wizyta została zarezerwowana. Informacja potwierdzająca została wysłana na maila!');
                }
            }
            
            return redirect()->route('welcome');
        }
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
        $allIntervals = new Collection();
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
                            $allIntervals->push($interval);
                            
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
                                $interval->available_units = $subscription->quantity;
                                $interval->purchase_id = $intervalWithAppointments['interval']->purchase_id;
                                
                                $bossMainInterval = Interval::where([
                                    'purchase_id' => $purchase->id,
                                    'substart_id' => $substart->id,
                                    'interval_id' => null,
                                    'start_date' => is_object($bossIntervalStartDate) ? $bossIntervalStartDate->format('Y-m-d') : $bossIntervalStartDate,
                                    'end_date' => $endDate
                                ])->first();
                                
                                $bossMainInterval->workers_available_units = $bossMainInterval->workers_available_units + $subscription->quantity;
                                $bossMainInterval->save();
                                
                                $interval->interval_id = $bossMainInterval->id;
                                $interval->save();
                                    
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
                                        
                                        $interval->available_units = $interval->available_units - 1;
                                        $interval->save();

                                        $bossMainInterval->workers_available_units = $bossMainInterval->workers_available_units - 1;
                                        $bossMainInterval->save();
                                    }
                                }

                                $intervalWithAppointments['interval']->delete();
                                
                                $bossIntervalStartDate = date('Y-m-d', strtotime("+1 month", strtotime($bossIntervalStartDate)));
                                $bossIntervalStartDate = date('Y-m-d', strtotime("-1 day", strtotime($bossIntervalStartDate)));
                            }
                            
                        } elseif ($intervalWithAppointments['interval']->substart_id !== null) {
                            
                            for ($i = 1; $i <= $subscription->duration; $i++)
                            {
                                $interval = new Interval();
                                $interval->available_units = $subscription->quantity;
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

                                            $interval->available_units = $interval->available_units - 1;
                                            $interval->save();
                                        }
                                    }

                                    $intervalWithAppointments['interval']->delete();
                                }
                            }
                        }
                    }
                }
            }
            
            if (count($allIntervals) > 0)
            {
                foreach ($allIntervals as $interval)
                {
                    $intervalAppointments = Appointment::where('interval_id', $interval->id)->get();
                    
                    if (count($intervalAppointments) > 0)
                    {
                        foreach ($intervalAppointments as $intervalAppointment)
                        {
                            $intervalAppointment->delete();
                        }
                    }
                    
                    $interval->delete();
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
            
            $dayGraphicCount = Graphic::where('day_id', $days[$i]->id)->get();
            $days[$i]['dayGraphicCount'] = count($dayGraphicCount);
            
            $daysArray[] = $days[$i];
        }
        
        return $daysArray;
    }
    
    private function formatGraphicAndAppointments($graphicTime, $chosenDay) 
    {
        $graphic = [];
        
        if ($graphicTime !== null)
        {
            $timeZone = new \DateTimeZone("Europe/Warsaw");
            $now = new \DateTime(null, $timeZone);
            
            $workUnits = ($graphicTime->total_time / 20);
            $startTime = date('G:i', strtotime($graphicTime->start_time));
            
            for ($i = 0; $i < $workUnits; $i++) 
            {
                $appointment = Appointment::where([
                    'day_id' => $chosenDay->id,
                    'graphic_id' => $graphicTime->id,
                    'start_time' => $startTime
                ])->with('user')->first();
                
                if ($appointment !== null)
                {
                    $limit = $appointment->minutes / 20;
                    
                    if ($limit > 1)
                    {
                        $time = [$startTime];

                        for ($j = 1; $j < $limit; $j++)
                        {
                            $time[] = date('G:i', strtotime("+20 minutes", strtotime($time[count($time) - 1])));
                            $workUnits -= 1;
                        }
                        
                    } else {
                        
                        $time = $startTime;
                    }
                    
                    $graphic[] = [
                        'time' => $time,
                        'appointment' => $appointment,
                        'appointmentLimit' => $limit,
                    ];
                    
                    $timeIncrementedByAppointmentMinutes = strtotime($appointment->end_time, strtotime($startTime));
                    $startTime = date('G:i', $timeIncrementedByAppointmentMinutes);
                    
                } else {
                    
                    $graphic[] = [
                        'time' => $startTime,
                        'appointment' => null,
                        'appointmentLimit' => 0,
                    ];
                    
                    $timeIncrementedBy20Minutes = strtotime("+20 minutes", strtotime($startTime));
                    $startTime = date('G:i', $timeIncrementedBy20Minutes);
                }
            }
        }
        
        return $graphic;
    }
    
    private function checkIfPreviewMonthIsAvailable($property, $year, $month)
    {
        if ($month->month_number == 1)
        {
            $year = Year::where([
                'property_id' => $property->id,
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
    
    private function checkIfNextMonthIsAvailable($property, $year, $month)
    {
        if ($month->month_number == 12)
        {
            $year = Year::where([
                'property_id' => $property->id,
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
