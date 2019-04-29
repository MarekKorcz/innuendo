<?php

namespace App\Http\Controllers;

use App\Graphic;
use App\Appointment;
use App\Calendar;
use App\Category;
use App\Item;
use App\Year;
use App\Month;
use App\Day;
use App\Subscription;
use App\Purchase;
use App\Interval;
use App\ChosenProperty;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class AppointmentController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['beforeShowCreatePage']);
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
            
            if (auth()->user() !== null)
            {
                return redirect()->action(
                    'AppointmentController@create'
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
    public function create(Request $request)
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
                                $appointmentTermIncremented = date('G:i', strtotime("+30 minutes", strtotime($appointmentTermIncremented)));

                                if ((int)$appointmentTermIncremented >= (int)$startTime && (int)$appointmentTermIncremented < (int)$endTime)
                                {
                                    $nextAppointmentAvailable = Appointment::where([
                                        'graphic_id' => $graphicId,
                                        'start_time' => $appointmentTermIncremented
                                    ])->first();

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

                            $calendar = Calendar::where('id', $calendarId)->first();

                            if ($calendar !== null)
                            {
                                $categories = Category::all();

                                if (count($categories) > 0)
                                {                                    
                                    $appointmentLengthInMinutes = $appointmentLength * 30;
                                    
                                    $items = new Collection();
                                    
                                    $chosenProperty = ChosenProperty::where([
                                        'user_id' => auth()->user()->id,
                                        'property_id' => $calendar->property_id
                                    ])->with('purchases')->first();
                                    
                                    $userSubscriptionItems = new Collection();
                                    
                                    if ($chosenProperty !== null && $chosenProperty->purchases)
                                    {
                                        foreach ($chosenProperty->purchases as $purchase)
                                        {
                                            $subscription = Subscription::where('id', $purchase->subscription_id)->with('items')->first();
                     
                                            // chosen date
                                            $chosenDay = (string)$day;
                                            $chosenDay = strlen($chosenDay) == 1 ? '0' . $chosenDay : $chosenDay;
                                            $chosenMonth = (string)$month;
                                            $chosenMonth = strlen($chosenMonth) == 1 ? '0' . $chosenMonth : $chosenMonth;

                                            $chosenDate = new \DateTime($year . '-' . $chosenMonth . '-' . $chosenDay);

                                            // purchase intervals
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
                                                    foreach ($subscription->items as $item)
                                                    {
                                                        if ($item->minutes <= $appointmentLengthInMinutes)
                                                        {
                                                            $item['subscription_id'] = $subscription->id;
                                                            $item['subscription_name'] = $subscription->name;
                                                            $userSubscriptionItems = $userSubscriptionItems->push($item);
                                                        }
                                                    }
                                                }
                                            }                                          
                                        }
                                    }
                                    
                                    if ($userSubscriptionItems !== null)
                                    {
                                        $items = $items->merge($userSubscriptionItems);
                                    }
                                    
                                    foreach ($categories as $category)
                                    {                                    
                                        $categoryItems = Item::where('category_id', $category->id)->where('minutes', '<=', $appointmentLengthInMinutes)->get();
                                        
                                        if ($categoryItems !== null)
                                        {
                                            $items = $items->merge($categoryItems);
                                        }
                                    }
                                }
                            }
                            
                            return view('appointment.create')->with([
                                'appointmentTerm' => $appointmentTerm,
                                'calendarId' => $calendarId,
                                'graphicId' => $graphicId,
                                'year' => $year,
                                'month' => $month,
                                'day' => $day,
                                'items' => count($items) > 0 ? $items : []
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
    public function store(Request $request)
    {
        $appointmentTerm = $request->get('appointmentTerm');
        $item = $request->get('item');
        $calendarId = $request->get('calendarId');
        $graphicId = $request->get('graphicId');
        $year = $request->get('year');
        $month = $request->get('month');
        $day = $request->get('day');
        
        if ($appointmentTerm !== null && is_integer((int)$appointmentTerm) &&
            $item !== null && is_integer((int)$item) &&
            $calendarId !== null && is_integer((int)$calendarId) &&
            $graphicId !== null && is_integer((int)$graphicId) &&
            $year !== null && is_integer((int)$year) &&
            $month !== null && is_integer((int)$month) &&
            $day !== null && is_integer((int)$day))
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
                    $appointment->user_id = auth()->user()->id;
                    $appointment->item_id = $item->id;
                    
                    if ($request->get('subscription_id'))
                    {
                        $subscriptionId = htmlentities((int)$request->get('subscription_id'), ENT_QUOTES, "UTF-8");
                        $subscription = Subscription::where('id', $subscriptionId)->with('items')->first();
                        
                        if ($subscription !== null)
                        {
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
                                    'user_id' => auth()->user()->id,
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
                                        // chosen date                                      
                                        $chosenDay = (string)$day->day_number;
                                        $chosenDay = strlen($chosenDay) == 1 ? '0' . $chosenDay : $chosenDay;
                                        $chosenMonth = (string)$month->month_number;
                                        $chosenMonth = strlen($chosenMonth) == 1 ? '0' . $chosenMonth : $chosenMonth;

                                        $chosenDate = new \DateTime($year->year . '-' . $chosenMonth . '-' . $chosenDay);

                                        // purchase intervals
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
                                                
                                                // todo: zobacz czy wizyty które są abonamentowe mogą być robione poza czasem trwania 
                                                // wykupionej subskrypcji
                                               
                                                $appointment->interval_id = $interval->id;

                                            } else {

                                                return redirect()->action(
                                                    'UserController@calendar', [
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
                        'UserController@appointmentShow', [
                            'id' => $appointment->id
                        ]
                    )->with('success', 'Wizyta została zarezerwowana. Informacja potwierdzająca została wysłana na maila!');
                    
                }
                
                return redirect()->action(
                    'UserController@calendar', [
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
}
