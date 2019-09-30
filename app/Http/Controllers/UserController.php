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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Redirect;
use Illuminate\Support\Collection;

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
            'employeesList', 
            'employee',
            'calendar'
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

                    $user = User::where('id', $user->id)->with('chosenProperties')->first();

                    $calendarsAvailableToWorker = new Collection();

                    if (count($user->chosenProperties) > 0 && count($calendars) > 0)
                    {
                        foreach ($calendars as $calendar)
                        {
                            foreach ($user->chosenProperties as $chosenProperty)
                            {
                                if ($calendar->property->id === $chosenProperty->property_id)
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
        
        $properties = Property::where('boss_id', null)->get();
        
        if ($properties !== null)
        {
            if (count($bossProperties) > 0)
            {
                foreach ($bossProperties as $bossProperty)
                {
                    $properties->push($bossProperty);
                }
            }
            
            if (count($properties) == 1)
            {
                return redirect()->action(
                    'UserController@property', [
                        'id' => $properties->first()->id,
                    ]
                );
            }

            foreach ($properties as $property)
            {
                if ($property->boss_id !== null)
                {
                    $property['isPurchased'] = true;
                    
                } else {
                    
                    $property['isPurchased'] = false;
                }
            }

            return view('user.property_index')->with([
                'properties' => $properties->sortBy('isPurchased', SORT_REGULAR, true)
            ]);
        }
        
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
        if (is_integer((int)$id) && $id !== null)
        {
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
                        }
                        else
                        {
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
                        }
                        else
                        {
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
        $appointment = Appointment::where('id', $id)->with('purchase')->first();
        
        if ($appointment !== null)
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
                            $bossMainInterval = Interval::where('id', $appointmentInterval->interval_id)->first();
                            $bossMainInterval->available_units = ($bossMainInterval->available_units + 1);
                            $bossMainInterval->save();

                        } elseif ($appointmentInterval->substart_id !== null) {

                            $appointmentInterval->available_units = ($appointmentInterval->available_units + 1);
                            $appointmentInterval->save();
                        }
                    }
                }
                
                $appointment->delete();

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
                    'UserController@appointmentIndex'
                )->with('success', 'Wizyta została usunięta!');
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
                ])->first();
                
                $appointmentId = 0;
                
                $explodedStartTime = explode(":", $startTime);
                $chosenDayDateTime->setTime($explodedStartTime[0], $explodedStartTime[1], 0);
                
                if ($appointment !== null && auth()->user() !== null)
                {
                    $appointmentId = $appointment->user_id == auth()->user()->id ? $appointment->id  : 0;
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
     * Shows a list of all property with subscriptions to buy.
     * 
     * @return type
     */
    public function propertiesSubscription()
    {        
        $properties = Property::where('boss_id', null)->with('subscriptions')->get();
        
        if (count($properties) > 0)
        {         
            if (count($properties) == 1)
            {
                return redirect()->action(
                    'UserController@propertySubscriptionList', [
                        'id' => $properties->first()->id
                    ]
                );
                
            } else {
                
                return view('user.property_list')->with([
                    'properties' => $properties
                ]);
            }
        }
        
        return redirect()->route('welcome');
    }
    
    /**
     * Shows a list of all subscriptions available for users to buy.
     * 
     * @param type $id
     * @return type
     */
    public function propertySubscriptionList($id)
    {
        $propertyId = htmlentities((int)$id, ENT_QUOTES, "UTF-8");
        $propertyId = (int)$propertyId;
        
        $property = Property::where('id', $propertyId)->with('subscriptions')->first();
        
        if ($property !== null && $property->subscriptions)
        {            
            $subscriptions = $property->subscriptions;
            
            foreach ($subscriptions as $subscription)
            {
                $subscription['purchase_id'] = null;
            }
            
            $user = User::where('id', auth()->user()->id)->with('chosenProperties')->first();
                    
            if ($user->chosenProperties)
            {
                foreach ($user->chosenProperties as $chosenProperty)
                {
                    if ($chosenProperty->property_id == $property->id)
                    {
                        $chosenProperty = ChosenProperty::where('id', $chosenProperty->id)->with('purchases')->first();
                                                
                        if ($chosenProperty->purchases)
                        {
                            foreach ($subscriptions as $subscription)
                            {
                                foreach ($chosenProperty->purchases as $purchase)
                                {
                                    if ($subscription->id == $purchase->subscription_id)
                                    {
                                        $subscription['purchase_id'] = $purchase->id;
                                    }
                                }
                            }
                        }
                    }
                }
            }

            return view('user.subscription_list')->with([
                'property' => $property,
                'subscriptions' => $subscriptions
            ]);
        }
        
        return redirect()->route('welcome');
    }
    
    /**
     * Shows the chosen subscription view.
     * 
     * @param type $propertyId
     * @param type $subscriptionId
     */
    public function subscriptionShow($propertyId, $subscriptionId)
    {
        $propertyId = htmlentities((int)$propertyId, ENT_QUOTES, "UTF-8");
        $propertyId = (int)$propertyId;
        
        $subscriptionId = htmlentities((int)$subscriptionId, ENT_QUOTES, "UTF-8");
        $subscriptionId = (int)$subscriptionId;
        
        $property = Property::where('id', $propertyId)->first();
        $subscription = Subscription::where('id', $subscriptionId)->first();
        
        $user = User::where('id', auth()->user()->id)->with('chosenProperties')->first();
        
        if ($subscription !== null && $property !== null && 
           ($property->boss_id == null || ($property->boss_id !== null && $user->getBoss() !== null && $user->getBoss()->id == $property->boss_id)))
        {
            $isPurchasable = true;

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
                                $purchasedSubscription = Subscription::where('id', $purchase->subscription_id)->first();

                                if ($subscription !== null && $subscription->id == $purchasedSubscription->id)
                                {
                                    $subscription['purchase_id'] = $purchase->id;
                                    $isPurchasable = false;
                                    break;
                                }
                            }
                        }
                    }
                }
            }
            
            return view('user.subscription_show')->with([
                'property' => $property,
                'subscription' => $subscription,
                'isPurchasable' => $isPurchasable
            ]);
        }
        
        return redirect()->route('welcome');
    }
    
    /**
     * Shows subscription's purchase view.
     * 
     * @param type $propertyId
     * @param type $subscriptionId
     * @return type
     */
    public function subscriptionPurchase($propertyId, $subscriptionId)
    {
        $propertyId = htmlentities((int)$propertyId, ENT_QUOTES, "UTF-8");
        $propertyId = (int)$propertyId;
        
        $subscriptionId = htmlentities((int)$subscriptionId, ENT_QUOTES, "UTF-8");
        $subscriptionId = (int)$subscriptionId;
        
        $property = Property::where('id', $propertyId)->first();
        $subscription = Subscription::where('id', $subscriptionId)->first();
        
        $user = User::where('id', auth()->user()->id)->with('chosenProperties')->first();
        
        if ($subscription !== null && $property !== null && 
           ($property->boss_id == null || ($property->boss_id !== null && $user->getBoss() !== null && $user->getBoss()->id == $property->boss_id)))
        {
            // zrób tak żeby usery należące do szefa nie mogły wykupywać sobie jego subskrypcji!!
            
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
                                $userSubscription = Subscription::where('id', $purchase->subscription_id)->first();

                                if ($userSubscription !== null && $userSubscription->id == $subscription->id)
                                {
                                    return redirect()->action(
                                        'UserController@subscriptionPurchasedShow', [
                                            'id' => $purchase->id
                                        ]
                                    )->with('success', 'Posiadasz już te subskrypcje');
                                }
                            }
                        }
                    }
                }
            }
            
            if ($property->boss_id !== null && $property->boss_id !== $user->id)
            {
                return redirect()->route('welcome')->with('error', 'Nie posiadasz uprawnień do wykupywania pakietu z prywatnej lokalizacji');
                
            } else {
                
                return view('user.subscription_purchase')->with([
                    'property' => $property,
                    'subscription' => $subscription
                ]);
            }
        }
        
        return redirect()->route('welcome');
    }
    
    /**
     * Subscription purchase method.
     * 
     * @param Request $request
     * @return type
     */
    public function subscriptionPurchased(Request $request)
    {
        $rules = array(
            'terms'             => 'required',
            'property_id'       => 'required',
            'subscription_id'   => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return Redirect::to('user/subscription/purchase/' . Input::get('property_id') . '/' . Input::get('subscription_id'))
                ->withErrors($validator);
        } else {
            
            $subscription = Subscription::where('id', Input::get('subscription_id'))->first();
            $property = Property::where('id', Input::get('property_id'))->first();
            
            if ($subscription !== null && $property !== null)
            {
                // check if such a subscription hasn't already been purchased!
                $isPurchasable = true;

                $user = User::where('id', auth()->user()->id)->with('chosenProperties')->first();

                if (count($user->chosenProperties) > 0)
                {
                    foreach ($user->chosenProperties as $chosenProperty)
                    {
                        if ($chosenProperty->property_id == Input::get('property_id'))
                        {
                            $chosenProperty = ChosenProperty::where('id', $chosenProperty->id)->with('purchases')->first();

                            if ($chosenProperty->purchases)
                            {
                                foreach ($chosenProperty->purchases as $purchase)
                                {
                                    if ($purchase->subscription_id == Input::get('subscription_id'))
                                    {
                                        $isPurchasable = false;
                                    }
                                }
                            }
                        }
                    }
                }

                if ($isPurchasable)
                {
                    $chosenProperty = ChosenProperty::where([
                        'property_id' => $property->id,
                        'user_id' => auth()->user()->id
                    ])->first();
                    
                    if ($chosenProperty === null)
                    {
                        $chosenProperty = new ChosenProperty();
                        $chosenProperty->property_id = $property->id;
                        $chosenProperty->user_id = auth()->user()->id;
                        $chosenProperty->save();
                    }
                    
                    // store
                    $purchase = new Purchase();
                    $purchase->subscription_id = $subscription->id;
                    $purchase->chosen_property_id = $chosenProperty->id;
                    $purchase->save();
                    
                    $startDate = date('Y-m-d');
                    
                    $substart = new Substart();
                    $substart->start_date = $startDate;
                    $endDate = date('Y-m-d', strtotime("+" . ($subscription->duration - 1) . " month", strtotime($startDate)));
                    $substart->end_date = $endDate;
                    $substart->user_id = auth()->user()->id;
                    $substart->property_id = $property->id;
                    $substart->subscription_id = $subscription->id;
                    $substart->purchase_id = $purchase->id;
                    $substart->save();
                    
                    $purchase->substart_id = $substart->id;
                    $purchase->save();

                    if ($purchase !== null)
                    {
                        $startDate = date('Y-m-d');
                                    
                        $interval = new Interval();
                        $interval->available_units = $subscription->quantity * $subscription->duration;

                        $interval->start_date = $startDate;
                        $startDate = date('Y-m-d', strtotime("+" . ($subscription->duration - 1) . " month", strtotime($startDate)));

                        $endDate = date('Y-m-d', strtotime("-1 day", strtotime($startDate)));
                        $interval->end_date = $endDate;

                        $interval->purchase_id = $purchase->id;
                        $interval->save();

                        /**
                         * 
                         * 
                         * 
                         * 
                         * Email sending
                         * 
                         * 
                         * 
                         * 
                         * 
                         */

                        // redirect
                        return redirect()->action(
                            'UserController@subscriptionPurchasedShow', [
                                'id' => $purchase->id
                            ]
                        )->with('success', 'Subskrypcja dodana. Wiadomość z informacjami została wysłana na maila');
                    }
                }
            }
        }
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
                    $user = User::where('id', auth()->user()->id)->with('chosenProperties')->first();
                    
                    if ($user !== null && $user->boss_id !== null && count($user->chosenProperties) > 0)
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
     * Shows a list of purchased subscriptions assigned to current user.
     * 
     * @return type
     */
    public function purchasedSubscriptionPropertyList() 
    {
        $user = User::where('id', auth()->user()->id)->with('chosenProperties')->first();
        $properties = new Collection();
        
        foreach ($user->chosenProperties as $chosenProperty)
        {
            $chosenProperty = ChosenProperty::where('id', $chosenProperty->id)->with('property')->first();
            
            if ($chosenProperty->property !== null)
            {                
                $properties = $properties->push($chosenProperty->property);
            }
        }
        
        if (count($properties) > 0)
        {          
            if (count($properties) == 1)
            {
                return redirect()->action(
                    'UserController@purchasedSubscriptionList', [
                        'propertyId' => $properties->first()->id
                    ]
                );
                
            } else {
                
                return view('user.subscription_purchased_property_list')->with([
                    'properties' => $properties
                ]);
            }
            
        } else {
            
            return redirect()->route('home')->with('error', "Nie posiadasz żadnej wykupionej subskrypcji");
        }
    }
    
    /**
     * Shows a list of purchased subscriptions assigned to passed user's property.
     * 
     * @param type $propertyId
     * @return type
     */
    public function purchasedSubscriptionList($propertyId) 
    {
        $propertyId = htmlentities((int)$propertyId, ENT_QUOTES, "UTF-8");
        $propertyId = (int)$propertyId;
        
        $property = Property::where('id', $propertyId)->first();
        $user = User::where('id', auth()->user()->id)->with('chosenProperties')->first();
        
        if ($property !== null && 
           ($property->boss_id == null || ($property->boss_id !== null && $user->getBoss() !== null && $user->getBoss()->id == $property->boss_id)))
        {
            if ($user->chosenProperties)
            {
                $subscriptions = new Collection();
                
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

                                $subscription = $purchase->subscription;
                                $subscription['purchase_id'] = $purchase->id;
                                $subscriptions = $subscriptions->push($subscription);
                            }
                        }
                    }
                }
                
                if (count($subscriptions) > 0 && $property !== null)
                {            
                    return view('user.subscription_purchased_list')->with([
                        'subscriptions' => $subscriptions,
                        'property' => $property
                    ]);
                }
            }
        }
        
        return redirect()->route('welcome');
    }
}
