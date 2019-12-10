<?php

namespace App\Http\Controllers;

use App\Calendar;
use App\Graphic;
use App\GraphicRequest;
use App\Property;
use App\User;
use App\Year;
use App\Month;
use App\Day;
use App\Appointment;
use App\Subscription;
use App\ChosenProperty;
use App\Purchase;
use App\Interval;
use App\Substart;
use App\Mail\AppointmentDestroy;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except([
//            'employeesList', 
//            'employee'
        ]);
    }
    
    /**
     * Shows employees.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function employeesList()
    {
        $employees = User::where('isEmployee', 1)->get();
        
        if ($employees !== null)
        {
            $employeesArray = [];

            for ($i = 0; $i < count($employees); $i++)
            {
                $employeesArray[$i + 1] = $employees[$i];
            }

            return view('employee.index')->with('employees', $employeesArray);
        }
        
        return redirect()->route('welcome');
    }
    
    /**
     * Shows employee.
     *
     * @param type $slug
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function employee($slug)
    {
        $employee = User::where([
            'isEmployee' => 1,
            'slug' => $slug
        ])->first();
        
        if ($employee !== null)
        {
            $user = auth()->user();
            
            $employeeCreatedAt = $employee->created_at->format('d.m.Y');
            $calendars = new Collection();
            $properties = [];        
        
            if ($user !== null)
            {
                $calendars = Calendar::where([
                    'employee_id' => $employee->id,
                    'isActive' => 1
                ])->with('property')->get();
                
                if ($user->isBoss) 
                {                
                    if (count($calendars) > 0)
                    {
                        foreach ($calendars as $key => $calendar)
                        {
                            if ($calendar->property !== null)
                            {
                                if ($calendar->property->boss_id !== $user->id)
                                {
                                    $calendars->forget($key);
                                }
                            }
                        }
                    }

                } else if ($user->boss_id !== null) {
                    
                    $user->load('chosenProperties');
                    
                    $calendarsAvailableToWorker = new Collection();

                    if (count($user->chosenProperties) > 0 && count($calendars) > 0)
                    {
                        foreach ($calendars as $calendar)
                        {
                            foreach ($user->chosenProperties as $chosenProperty)
                            {
                                if ($calendar->property !== null && $calendar->property->id === $chosenProperty->property_id)
                                {
                                    $calendarsAvailableToWorker->push($calendar);
                                }
                            }
                        }
                    }
                    
                    if (count($calendarsAvailableToWorker) > 0)
                    {
                        $calendars = new Collection();

                        foreach ($calendarsAvailableToWorker as $calendar)
                        {
                            $calendars->push($calendar);
                        }
                    }
                }

                for ($i = 0; $i < count($calendars); $i++)
                {
                    $properties[$i] = Property::where('id', $calendars[$i]->property_id)->first();
                }
            }
            
            $calendarsArray = [];

            if (count($calendars) > 0)
            {
                for ($i = 0; $i < count($calendars); $i++)
                {
                    $calendarsArray[$i + 1] = $calendars[$i];
                }
            }

            return view('employee.show')->with([
                'employee' => $employee,
                'employeeCreatedAt' => $employeeCreatedAt,
                'calendars' => $calendarsArray,
                'properties' => $properties
            ]);
        }
        
        return redirect()->route('welcome');
    }
    
    /**
     * Shows properties.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function propertiesList()
    {
        $bossId = 0;
        $user = auth()->user();
        $bossProperties = new Collection();
        
        if ($user !== null)
        {
            if ($user->boss_id !== null)
            {
                $bossId = $user->boss_id;

            } else if ($user->isBoss !== null) {

                $bossId = $user->id;
            }

            if ($bossId !== 0)
            {
                $bossProperties = Property::where('boss_id', $bossId)->get();
            }
        }
        
//        $properties = Property::where('boss_id', null)->get();
        
//        if ($properties !== null)
//        {
//            if (count($bossProperties) > 0)
//            {
//                foreach ($bossProperties as $bossProperty)
//                {
//                    $properties->push($bossProperty);
//                }
//            }
            
            if (count($bossProperties) == 1)
            {
                return redirect()->action(
                    'UserController@property', [
                        'id' => $bossProperties->first()->id,
                    ]
                );
            }

//            foreach ($properties as $property)
//            {
//                if ($property->boss_id !== null)
//                {
//                    $property['isPurchased'] = true;
//                    
//                } else {
//                    
//                    $property['isPurchased'] = false;
//                }
//            }

            return view('user.property_index')->with([
                'properties' => $bossProperties
            ]);
//        }
        
        return redirect()->route('welcome');
    }
    
    /**
     * Shows property.
     * 
     * @param type $id
     * @return type
     */
    public function property($id)
    {      
        
        
        // todo: zrób tak żeby w tym widoku pracownik mógł widzieć tylko property swojego szefa, szef property swoje, a employee jeśli też używa, żeby widział wszystkie...
        // albo wypierdol wszystko do swoich kontrolerów korzystających po prostu z jednego widoku??
        
        
        
        $property = Property::where('id', $id)->first();

        if ($property !== null)
        {
            $propertyCreatedAt = $property->created_at->format('d.m.Y');
            $calendars = Calendar::where([
                'property_id' => $property->id,
                'isActive' => 1
            ])->get();
            $employees = new Collection();

            if (count($calendars) > 0)
            {
                foreach ($calendars as $calendar)
                {
                    $employee = User::where('id', $calendar->employee_id)->first();

                    if ($employee !== null)
                    {
                        $employee['calendar'] = $calendar->id;

                        $employees->push($employee);
                    }
                }
            }

            $today = [
                'year' => date('Y'),
                'month' => date('n'),
                'day' => date('j')
            ];

            return view('user.property_show')->with([
                'property' => $property,
                'propertyCreatedAt' => $propertyCreatedAt,
                'employees' => $employees,
                'today' => $today
            ]);
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
                
            } else if (is_numeric($year) && (int)$year > 0) {
                
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
                    
                } else if (is_numeric($month_number) && (int)$month_number > 0 || (int)$month_number <= 12) {
                    
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
                            
                        } else {
                            
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
                        
                        $property = Property::where('id', $calendar->property_id)->first();
                        
                        $canSendRequest = $property->boss_id == auth()->user()->id ? true : false;
                        $graphicRequest = null;
                        
                        if ($canSendRequest)
                        {
                            $graphicRequest = GraphicRequest::where([
                                'property_id' => $property->id,
                                'year_number' => $year->year,
                                'month_number' => $month->month_number,
                                'day_number' => $currentDay !== null ? $currentDay->day_number : $day_number,
                                'boss_id' => auth()->user()->id
                            ])->first();
                        }
                        
                        $employees = User::where('isEmployee', 1)->get();
                        
                        $employee = User::where([
                            'isEmployee' => 1,
                            'id' => $calendar->employee_id
                        ])->first();
                        
                        return view('employee.calendar')->with([
                            'calendar_id' => $calendar->id,
                            'employee_slug' => $employee->slug,
                            'availablePreviousMonth' => $availablePreviousMonth,
                            'availableNextMonth' => $availableNextMonth,
                            'year' => $year,
                            'month' => $month,
                            'days' => $days,
                            'current_day' => is_object($currentDay) ? $currentDay->day_number : 0,
                            'graphic' => $graphic,
                            'graphic_id' => $graphicTime ? $graphicTime->id : null,
                            'canSendRequest' => $currentDay !== null ? $canSendRequest : false,
                            'graphicRequest' => $graphicRequest,
                            'employees' => $employees
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
     * Shows an appointment assigned to current user.
     * 
     * @param type $id
     * @return type
     */
    public function appointmentShow($id)
    {
        if ($id !== null)
        {
            $appointment = Appointment::where([
                'id' => $id,
                'user_id' => auth()->user()->id
            ])->with([
                'item',
                'purchase'
            ])->first();

            if ($appointment !== null)
            {
                $day = Day::where('id', $appointment->day_id)->first();
                $month = Month::where('id', $day->month_id)->first();
                $year = Year::where('id', $month->year_id)->first();
                
                // appointment date                           
                $appointmentDay = (string)$day->day_number;
                $appointmentDay = strlen($appointmentDay) == 1 ? '0' . $appointmentDay : $appointmentDay;
                $appointmentMonth = (string)$month->month_number;
                $appointmentMonth = strlen($appointmentMonth) == 1 ? '0' . $appointmentMonth : $appointmentMonth;
    
                $now = new \DateTime(date('Y-m-d H:i:s'));
                $appointmentDate = new \DateTime($year->year . '-' . $appointmentMonth . '-' . $appointmentDay . ' ' . $appointment->start_time);
                
                $canBeDeleted = $now < $appointmentDate ? true : false;
                
                $subscription = $appointment->purchase ? Subscription::where('id', $appointment->purchase->subscription_id)->first() : false;
                
                $calendar = Calendar::where('id', $year->calendar_id)->first();
                
                $employee = User::where('id', $calendar->employee_id)->first();
                $property = Property::where('id', $calendar->property_id)->first();
                
                return view('user.appointment_show')->with([
                    'appointment' => $appointment,
                    'canBeDeleted' => $canBeDeleted,
                    'subscription' => $subscription,
                    'day' => $day->day_number,
                    'month' => $month->month,
                    'month_number' => $month->month_number,
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
        $user = User::where('id', auth()->user()->id)->with('chosenProperties')->first();
        $appointments = Appointment::where('user_id', $user->id)->with('item')->get();
        
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
                
                // appointment date                           
                $appointmentDay = (string)$day->day_number;
                $appointmentDay = strlen($appointmentDay) == 1 ? '0' . $appointmentDay : $appointmentDay;
                $appointmentMonth = (string)$month->month_number;
                $appointmentMonth = strlen($appointmentMonth) == 1 ? '0' . $appointmentMonth : $appointmentMonth;
                $appointment['date_time'] = new \DateTime($year->year . '-' . $appointmentMonth . '-' . $appointmentDay . ' ' . $appointment->start_time);
                
                $address = $property->street . ' ' . $property->street_number . '/' . $property->house_number . ', ' . $property->city;
                $appointment['address'] = $address;
                
                $appointment['employee'] = $employee->name . " " . $employee->surname;
                $appointment['employee_slug'] = $employee->slug;
            }
            
            $property = null;
            
            if (count($appointments) == 0)
            {
                if (count($user->chosenProperties) > 0)
                {
                    $property = Property::where('id', $user->chosenProperties->first()->property_id)->first();
                }
            }
            
            return view('user.appointment_index')->with([
                'appointments' => $appointments->sortByDesc('date_time'),
                'property' => $property,
                'user' => $user
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
        $user = auth()->user();
        
        $appointment = Appointment::where('id', $id)->with([
            'purchase',
            'user'
        ])->first();
        
        if ($appointment !== null && $appointment->user->id == $user->id || $user->isAdmin || $user->isEmployee)
        {            
            if ($appointment->status !== 1)
            {
                if ($appointment->interval_id !== null)
                {
                    $appointmentInterval = Interval::where('id', $appointment->interval_id)->first();

                    if ($appointmentInterval !== null)
                    {
                        if ($appointmentInterval->substart_id === null)
                        {
                            // >> purchased subscription worker scenario
                            $bossMainInterval = Interval::where('id', $appointmentInterval->interval_id)->first();
                            $bossMainInterval->workers_available_units = ($bossMainInterval->workers_available_units + 1);
                            $bossMainInterval->save();
                            
                            $appointmentInterval->available_units = ($appointmentInterval->available_units + 1);
                            $appointmentInterval->save();
                            // <<

                        } else if ($appointmentInterval->substart_id !== null) {

                            // >> purchased subscription owner scenario
                            $appointmentInterval->available_units = ($appointmentInterval->available_units + 1);
                            $appointmentInterval->save();
                            // <<
                        }
                    }
                }
                
                $appointment->delete();
                
                if ($user->id == $appointment->user->id)
                {
                    \Mail::to($user)->send(new AppointmentDestroy($user, $appointment));

                    return redirect()->action(
                        'UserController@appointmentIndex'
                    )->with('success', 'Wizyta została usunięta!');
                    
                } else {
                    
                    return redirect()->action(
                        'WorkerController@backendAppointmentIndex', [
                            'id' => $appointment->user->id
                    ])->with('success', 'Wizyta została usunięta!');
                }
            }
            
            return redirect()->route('welcome')->with('error', 'Nie można usunąć już wykonanej wizyty');
        }
        
        return redirect()->route('welcome')->with('error', 'Wizyta o podanym id nie istnieje');
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
            
            $workUnits = ($graphicTime->total_time / 15);
            $startTime = date('G:i', strtotime($graphicTime->start_time));
            
            for ($i = 0; $i < $workUnits; $i++) 
            {
                $appointment = Appointment::where([
                    'day_id' => $chosenDay->id,
                    'start_time' => $startTime
                ])->with('user')->first();
                
                $appointmentId = 0;
                $bossWorkerAppointment = false;
                $ownAppointment = false;
                
                $explodedStartTime = explode(":", $startTime);
                $chosenDayDateTime->setTime($explodedStartTime[0], $explodedStartTime[1], 0);
                
                if ($appointment !== null && auth()->user() !== null)
                {
                    $boss = null;
                    $appointmentId = $appointment->id;
                    
                    if (auth()->user()->isBoss !== null)
                    {
                        $boss = auth()->user();
                                    
                        if (count($boss->getWorkers()) > 0)
                        {
                            foreach ($boss->getWorkers() as $worker)
                            {
                                if ($appointment->user_id == $worker->id)
                                {
                                    $bossWorkerAppointment = true;
                                }
                            }
                        }

                    }
                    
                    $ownAppointment = $appointment->user_id == auth()->user()->id ? true : false;
                }
                
                if ($appointment !== null)
                {
                    $limit = $appointment->minutes / 15;
                    
                    if ($limit > 1)
                    {
                        $time = array($startTime);

                        for ($j = 1; $j < $limit; $j++)
                        {
                            $time[] = date('G:i', strtotime("+15 minutes", strtotime($time[count($time) - 1])));
                            $workUnits -= 1;
                        }
                        
                    } else {
                        
                        $time = $startTime;
                    }
                    
                    $graphic[] = [
                        'time' => $time,
                        'appointment' => $appointment,
                        'appointmentLimit' => $limit,
                        'appointmentId' => $appointmentId,
                        'bossWorkerAppointment' => $bossWorkerAppointment,
                        'ownAppointment' => $ownAppointment,
                        'canMakeAnAppointment' => $chosenDayDateTime > $now ? true : false
                    ];
                    
                    $timeIncrementedByAppointmentMinutes = strtotime($appointment->end_time, strtotime($startTime));
                    $startTime = date('G:i', $timeIncrementedByAppointmentMinutes);
                    
                } else {
                    
                    $graphic[] = [
                        'time' => $startTime,
                        'appointment' => null,
                        'appointmentLimit' => 0,
                        'appointmentId' => $appointmentId,
                        'bossWorkerAppointment' => $bossWorkerAppointment,
                        'ownAppointment' => $ownAppointment,
                        'canMakeAnAppointment' => $chosenDayDateTime > $now ? true : false
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
    
    /**
     * Shows the purchased subscription view.
     * 
     * @param type $id
     */
    public function subscriptionPurchasedShow($id) 
    {
        $purchase = Purchase::where('id', $id)->with([
            'subscription',
            'chosenProperty'
        ])->first();
        
        $user = auth()->user();
        
        if ($user->isBoss)
        {            
            return redirect()->action(
                'BossController@workerAppointmentList', [
                    'substartId' => $purchase->substart_id,
                    'userId' => $user->id
                ]
            );
        }
        
        if ($purchase !== null && $purchase->chosenProperty->user_id == auth()->user()->id)
        {
            $expirationDate = null;
            $substartInterval = null;
            $intervalAvailableUnits = null;
            $substart = Substart::where('id', $purchase->substart_id)->first();
                    
            if ($substart->isActive)
            {
                $today = new \DateTime(date('Y-m-d'));
                        
                $subscriptionCreationDate = new \DateTime($purchase->subscription->created_at->format('Y-m-d'));
                $interval = new \DateInterval('P12M');
                $subscriptionCreationDate->add($interval);            
                $expirationDate = $subscriptionCreationDate->format('d - m - Y');
                
                $substartInterval = Interval::where('substart_id', $substart->id)
                                            ->where('start_date', '<=', $today)
                                            ->where('end_date', '>=', $today)
                                            ->first();
            
                if ($substartInterval !== null)
                {
                    if ($user !== null && $user->boss_id !== null)
                    {
                        $user->load('chosenProperties');
                        
                        if (count($user->chosenProperties) > 0)
                        {
                            foreach ($user->chosenProperties as $chosenProperty)
                            {                            
                                if ($purchase->chosenProperty->property_id == $chosenProperty->property_id)
                                {
                                    $userPurchase = Purchase::where([
                                        'chosen_property_id' => $chosenProperty->id,
                                        'substart_id' => $substart->id
                                    ])->first();

                                    if ($userPurchase !== null)
                                    {                                    
                                        $userInterval = Interval::where([
                                            'interval_id' => $substartInterval->id,
                                            'purchase_id' => $userPurchase->id
                                        ])->first();

                                        if ($userInterval !== null)
                                        {
                                            $intervalAvailableUnits = $purchase->subscription->quantity;

                                            $userAppointments = Appointment::where('interval_id', $userInterval->id)->get();

                                            if (count($userAppointments) > 0)
                                            {
                                                $intervalAvailableUnits = $intervalAvailableUnits - count($userAppointments);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }

            $appointments = Appointment::where('purchase_id', $purchase->id)->with('item')->get();
            
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
                
                // appointment date                           
                $appointmentDay = (string)$day->day_number;
                $appointmentDay = strlen($appointmentDay) == 1 ? '0' . $appointmentDay : $appointmentDay;
                $appointmentMonth = (string)$month->month_number;
                $appointmentMonth = strlen($appointmentMonth) == 1 ? '0' . $appointmentMonth : $appointmentMonth;
                $appointment['date_time'] = new \DateTime($year->year . '-' . $appointmentMonth . '-' . $appointmentDay . ' ' . $appointment->start_time);

                $address = $property->street . ' ' . $property->street_number . '/' . $property->house_number . ', ' . $property->city;
                $appointment['address'] = $address;

                $appointment['employee'] = $employee->name . " " . $employee->surname;
                $appointment['employee_slug'] = $employee->slug;
            }

            return view('user.subscription_purchased_show')->with([
                'purchase' => $purchase,
                'expirationDate' => $expirationDate,
                'appointments' => $appointments->sortByDesc('date_time'),
                'substartInterval' => $substartInterval,
                'intervalAvailableUnits' => $intervalAvailableUnits
            ]);
        }
        
        return redirect()->route('welcome')->with('error', 'Ten wykupiony pakiet nie należy do Ciebie');
    }
    
    /**
     * Shows list of subscriptions
     * 
     * @param type $substartId
     * @return type
     */
    public function subscriptionList($substartId = 0)
    {     
        $user = auth()->user();
        
        if ($user->boss_id !== null)
        {
            $boss = User::where('id', $user->boss_id)->first();

            $chosenSubstart = new Collection();
            $substartProperty = null;
            $substartSubscription = null;

            $substartId = htmlentities((int)$substartId, ENT_QUOTES, "UTF-8");
            $substartId = (int)$substartId;

            if ($substartId !== 0)
            {
                $chosenSubstart = Substart::where([
                    'id' => $substartId,
                    'boss_id' => $boss->id
                ])->get();
                
                if (count($chosenSubstart) == 1)
                {
                    $substartProperty = Property::where('id', $chosenSubstart->first()->property_id)->first();
                    $substartSubscription = Subscription::where('id', $chosenSubstart->first()->subscription_id)->first();
                }
            }

            $propertiesWithSubscriptions = [];
            $properties = Property::where('boss_id', $boss->id)->with('subscriptions')->get();

            if (count($properties) > 0)
            {
                foreach ($properties as $property)
                {
                    // >> checks if property is checked
                    $property['isChecked'] = false;

                    if ($substartProperty !== null && $substartProperty->id === $property->id)
                    {
                        $property['isChecked'] = true;
                    }
                    // <<

                    $subscriptions = new Collection();

                    if (count($property->subscriptions) > 0)
                    {
                        foreach ($property->subscriptions as $subscription)
                        {
                            // >> check if subscription is checked
                            $subscription['isChecked'] = false;

                            if ($substartSubscription !== null && $substartSubscription->id === $subscription->id)
                            {
                                $subscription['isChecked'] = true;
                            }
                            // <<
                            
                            $chosenProperty = ChosenProperty::where([
                                'user_id' => $user->id,
                                'property_id' => $property->id
                            ])->first();
                            
                            if ($chosenProperty !== null)
                            {                                
                                // >> check if subscription is purchased already
                                $purchases = Purchase::where([
                                    'subscription_id' => $subscription->id,
                                    'chosen_property_id' => $chosenProperty->id
                                ])->get();
                                
                                if (count($purchases) > 0)
                                {
                                    $today = new \DateTime(date('Y-m-d'));
    //                                $today = date('Y-m-d', strtotime("+3 month", strtotime($today->format("Y-m-d"))));

                                    foreach ($purchases as $purchase)
                                    {
                                        // >> when purchased, look for substart
                                        $substart = Substart::where([
                                            'id' => $purchase->substart_id,
                                            'boss_id' => $boss->id
                                        ])->first();

                                        if ($substart !== null)
                                        {                                                
                                            // >> check whether substart is current or not
                                            $substart['isCurrent'] = false;

                                            if ($substart->start_date <= $today && $substart->end_date >= $today)
                                            {
                                                $substart['isCurrent'] = true;
                                            }
                                            // <<

                                            // set substart to purchase
                                            $purchase['substart'] = $substart;
                                        }
                                        // <<
                                    }
                                    
                                    $subscription['purchases'] = $purchases;
                                    $subscriptions->push($subscription);
                                }
                                // <<
                            }
                        }
                    }         

                    $propertiesWithSubscriptions[] = [
                        'property' => $property,
                        'subscriptions' => $subscriptions
                    ];
                }
                
                if (count($propertiesWithSubscriptions) > 0)
                {
                    if (count($chosenSubstart) == 0)
                    {
                        $propertyId = null;
                        $subscriptionId = null;
                        
                        // >> if not set, mark one property as checked
                        $checkedPropertyCount = 0;

                        foreach ($propertiesWithSubscriptions as $propertyWithSubscriptions)
                        {
                            if ($propertyWithSubscriptions['property']->isChecked === true)
                            {
                                $checkedPropertyCount++;
                            }
                        }

                        if ($checkedPropertyCount === 0)
                        {
                            $propertiesWithSubscriptions[0]['property']->isChecked = true;
                            $propertyId = $propertiesWithSubscriptions[0]['property']->id;
                        }
                        // <<

                        // >> if not set, mark one subscription as checked
                        $checkedSubscriptionCount = 0;

                        foreach ($propertiesWithSubscriptions as $propertyWithSubscriptions)
                        {
                            if ($propertyWithSubscriptions['property']->isChecked == true)
                            {
                                if (count($propertyWithSubscriptions['subscriptions']) > 0)
                                {
                                    foreach ($propertyWithSubscriptions['subscriptions'] as $subscription)
                                    {
                                        if ($subscription->isChecked == true)
                                        {
                                            $checkedSubscriptionCount++;
                                        }
                                    }
                                }

                                if ($checkedSubscriptionCount === 0)
                                {
                                    $propertyWithSubscriptions['subscriptions']->first()->isChecked = true;
                                    $subscriptionId = $propertyWithSubscriptions['subscriptions']->first()->id;
                                }
                            }
                        }
                        // <<
                    
                        // >> if not set, get substarts attached to chosen property and subscription
                        $chosenSubstart = Substart::where([
                            'property_id' => $propertyId,
                            'subscription_id' => $subscriptionId
                        ])->get();
                        // <<
                    }
                    
                    if (count($propertiesWithSubscriptions) > 0 && count($chosenSubstart) > 0)
                    {         
                        return view('user.subscription_dashboard')->with([
                            'propertiesWithSubscriptions' => $propertiesWithSubscriptions,
                            'substart' => $chosenSubstart->last()
                        ]);
                        
                    } else {
                        
                        return redirect()->route('home')->with('error', 'Coś poszło nie tak');
                    }
                    
                } else {
                    
                    return redirect()->route('home')->with('error', 'Coś poszło nie tak');
                }

            } else {

                return redirect()->route('home')->with('error', 'Ta lokalizacja nie należy do Ciebie');
            }
        }
        
        return redirect()->route('home')->with('error', 'Twoje konto nie jest przydzielone do żadnego szefa');
    }
    
    public function getPropertySubscriptions(Request $request)
    {
        if ($request->request->all())
        {
            $user = auth()->user();
            $boss = User::where('id', $user->boss_id)->first();
        
            $propertyId = htmlentities((int)$request->get('propertyId'), ENT_QUOTES, "UTF-8");
            $property = Property::where([
                'id' => $propertyId,
                'boss_id' => $boss->id
            ])->with([
                'subscriptions',
                'chosenProperties'
            ])->first();

            $message = "Błąd zapytania";
            $type = "error";

            if ($property !== null)
            {
                $chosenProperty = ChosenProperty::where([
                    'user_id' => $user->id,
                    'property_id' => $property->id
                ])->first();
                
                if ($chosenProperty !== null)
                {
                    $purchaseEntities = Purchase::where('chosen_property_id', $chosenProperty->id)->get();
                    
                    if (count($purchaseEntities) > 0)
                    {
                        // >> get all purchased subscriptions attached to property 
                        $propertySubscriptions = new Collection();

                        foreach ($purchaseEntities as $purchaseEntity)
                        {
                            $subscription = Subscription::where('id', $purchaseEntity->subscription_id)->first();
                            
                            if ($propertySubscriptions->count() == 0)
                            {
                                $propertySubscriptions->push($subscription);
                                
                            } else if (!$propertySubscriptions->contains('id', $subscription->id)) {
                                
                                $propertySubscriptions->push($subscription);
                            }
                        }
                        // <<
                        
                        if ($propertySubscriptions->count() > 0)
                        {
                            $subscriptions = [];
                            
                            foreach ($propertySubscriptions as $subscription)
                            {
                                $subscriptions[] = [
                                    'id' => $subscription->id,
                                    'name' => $subscription->name,
                                    'name_description' => \Lang::get('common.label'),
                                    'description_description' => \Lang::get('common.description'),
                                    'description' => $subscription->description,
                                    'old_price' => $subscription->old_price . " zł " . \Lang::get('common.per_person'),
                                    'old_price_description' => \Lang::get('common.regular_price'),
                                    'new_price' => $subscription->new_price . " zł " . \Lang::get('common.per_person'),
                                    'new_price_description' => \Lang::get('common.price_with_subscription'),
                                    'quantity' => $subscription->quantity,
                                    'quantity_description' => \Lang::get('common.number_of_massages_to_use_per_month'),
                                    'duration' => $subscription->duration,
                                    'duration_description' => \Lang::get('common.subscription_duration'),
                                ];
                            }
                            
                            $message = "Subskrypcje danej lokalizacji zostały wczytane";
                            $type = "success";

                            $data = [
                                'type'    => $type,
                                'message' => $message,
                                'subscriptions' => $subscriptions
                            ];
                        }
                    }

                } else {

                    $message = "Dana lokalizacja nie posiada żadnego wykupionego pakietu";
                    $type = "error";

                    $data = [
                        'type'    => $type,
                        'message' => $message
                    ];
                }

                return new JsonResponse($data, 200, array(), true);
            }
            
        } else {
            
            $message = "Pusty request";
            $type = "error";
        }
        
        return new JsonResponse(array(
            'type'    => $type,
            'message' => $message            
        ));
    }
    
    public function getSubscriptionSubstarts (Request $request)
    {
        if ($request->get('propertyId') && $request->get('subscriptionId'))
        {
            $propertyId = htmlentities($request->get('propertyId'), ENT_QUOTES, "UTF-8");
            $subscriptionId = htmlentities($request->get('subscriptionId'), ENT_QUOTES, "UTF-8");
            
            $user = auth()->user();
            $boss = User::where('id', $user->boss_id)->first();
        
            $property = Property::where([
                'id' => $propertyId,
                'boss_id' => $boss->id
            ])->first();

            $subscription = Subscription::where('id', $subscriptionId)->first();

            if ($property !== null && $subscription !== null)
            {
                $userChosenProperty = ChosenProperty::where([
                    'user_id' => $user->id,
                    'property_id' => $property->id
                ])->first();

                if ($userChosenProperty !== null)
                {
                    $userPurchases = Purchase::where([
                        'chosen_property_id' => $userChosenProperty->id
                    ])->with('substart')->get();

                    $substarts = new \Illuminate\Database\Eloquent\Collection();

                    if (count($userPurchases) > 0)
                    {
                        foreach ($userPurchases as $userPurchase)
                        {
                            $bossSubstart = Substart::where([
                                'id' => $userPurchase->substart_id,
                                'boss_id' => $boss->id,
                                'property_id' => $property->id,
                                'subscription_id' => $subscription->id
                            ])->first();

                            if ($bossSubstart !== null)
                            {
                                $bossSubstart['purchase'] = $userPurchase;
                                $substarts->push($bossSubstart);
                            }
                        }
                    }                       

                    $substarts = $substarts->sortBy('end_date');
                    $newestSubstart = $substarts->last();

                    $data = [
                        'type'    => 'success',
                        'header'    => \Lang::get('common.subscription_duration_period'),
                        'substarts' => $this->turnSubstartObjectsToArrays($substarts),
                        'newestSubstart' => $this->turnSubstartObjectsToArrays($newestSubstart),
                        'lastSubstartId' => $substarts->last()->id,
                    ];

                    return new JsonResponse($data, 200, array(), true);
                }
            }
        }
        
        return new JsonResponse(array(
            'type'    => 'error',
            'message' => 'Pusty request',
            'no_people_assigned_to_subscription' => \Lang::get('common.no_people_assigned_to_subscription'),
        )); 
    }
    
    private function turnSubstartObjectsToArrays($substarts)
    {
        $substartArray = [];
        $today = new \DateTime(date('Y-m-d'));
        
        if (!is_a($substarts, 'Illuminate\Database\Eloquent\Collection') && $substarts !== null)
        {
            $isActiveMessage = "";
            
            if ($substarts->end_date < $today)
            {
                $isActiveMessage = \Lang::get('common.duration_is_over');
            
            } elseif ($substarts->start_date <= $today && $today <= $substarts->end_date) {
                
                $isActiveMessage = $substarts->isActive == 1 ? \Lang::get('common.activated') : \Lang::get('common.not_activated');
            }
                                        
            $substartArray[] = [
                'id' => $substarts->id,
                'start_date' => $substarts->start_date->format('Y-m-d'),
                'start_date_description' => \Lang::get('common.from'),
                'end_date' => $substarts->end_date->format('Y-m-d'),
                'end_date_description' => \Lang::get('common.to'),
                'button' => route('subscriptionPurchasedShow', [
                    'id' => $substarts->purchase->id
                ]),
                'button_description' => \Lang::get('common.appointments'),
                'isActiveMessage' => $isActiveMessage,
                'isActive' => $substarts->isActive,
            ];
            
        } else if (is_a($substarts, 'Illuminate\Database\Eloquent\Collection') && count($substarts) > 0) {
            
            foreach ($substarts as $substart)
            {
                $isActiveMessage = "";
                
                if ($substart->end_date < $today)
                {
                    $isActiveMessage = \Lang::get('common.duration_is_over');

                } elseif ($substart->start_date <= $today && $today <= $substart->end_date) {

                    $isActiveMessage = $substart->isActive == 1 ? \Lang::get('common.activated') : \Lang::get('common.not_activated');
                }
            
                $substartArray[] = [
                    'id' => $substart->id,
                    'start_date' => $substart->start_date->format('Y-m-d'),
                    'start_date_description' => \Lang::get('common.from'),
                    'end_date' => $substart->end_date->format('Y-m-d'),
                    'end_date_description' => \Lang::get('common.to'),
                    'button' => route('subscriptionPurchasedShow', [
                        'id' => $substart->purchase->id
                    ]),
                    'button_description' => \Lang::get('common.appointments'),
                    'isActiveMessage' => $isActiveMessage,
                    'isActive' => $substart->isActive
                ];
            }
        }
        
        return $substartArray;
    }
}
