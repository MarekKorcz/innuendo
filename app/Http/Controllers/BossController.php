<?php

namespace App\Http\Controllers;

use App\Code;
use App\Property;
use App\InvoiceData;
use App\GraphicRequest;
use App\Message;
use App\Subscription;
use App\ChosenProperty;
use App\User;
use App\Appointment;
use App\Day;
use App\Month;
use App\Year;
use App\Discount;
use App\Graphic;
use App\Substart;
use App\Purchase;
use App\Interval;
use App\PromoCode;
use App\Mail\SubscriptionPurchased;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Redirect;
use Session;

class BossController extends Controller
{    
    /**
     * Create a new controller instance.
     *
     * return void
     */
    public function __construct()
    {
        $this->middleware('boss');
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
    public function calendar($property_id, $year = 0, $month_number = 0, $day_number = 0)
    {
        $property = Property::where([
            'id' => $property_id,
            'boss_id' => auth()->user()->id
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
                            $chosenDayDateTime = new \DateTime($year->year . "-" . $month->month_number . "-" . $chosenDay->day_number);
                            $graphic = $this->formatGraphicAndAppointments($graphicTime, $currentDay, $chosenDayDateTime);
                            
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
                        
                        $canSendRequest = $property->boss_id == $property->boss->id ? true : false;
                        $graphicRequest = null;
                        
                        if ($canSendRequest)
                        {
                            $graphicRequest = GraphicRequest::where([
                                'property_id' => $property->id,
                                'day_id' => $currentDay !== null ? $currentDay->id : $day_number
                            ])->first();
                        }
                        
                        return view('boss.calendar')->with([
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
                            'canSendRequest' => $currentDay !== null ? $canSendRequest : false,
                            'graphicRequest' => $graphicRequest,
                            'employees' => User::where('isEmployee', 1)->get()
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
                'UserController@propertiesList'
            )->with('error', $message);
            
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
            $day = Day::where('id', $request->get('currentDayId'))->with('month.year')->first();
            
            if ($graphic !== null && $day !== null)
            {
                $chosenDayDateTime = new \DateTime($day->month->year->year . "-" . $day->month->month_number . "-" . $day->day_number);
                $graphicArray = $this->formatGraphicAndAppointments($graphic, $day, $chosenDayDateTime);
                
                foreach ($graphicArray as $key => $graphArr)
                {
                    if ($graphArr['appointment'] !== null)
                    {
                        $graphicArray[$key]['appointmentId'] = $graphArr['appointment']->id;
                        $graphicArray[$key]['appointmentUserId'] = $graphArr['appointment']->user->id;
                        $graphicArray[$key]['appointmentUserName'] = $graphArr['appointment']->user->name . " " . $graphArr['appointment']->user->surname;
                        $graphicArray[$key]['ownAppointmentHref'] = route('appointmentShow', [
                            'id' => $graphArr['appointment']->id
                        ]);
                        
                        unset($graphicArray[$key]['appointment']);
                    }
                }
                
                return new JsonResponse([
                    'type' => 'success',
                    'graphic' => $graphicArray,
                    'userId' => auth()->user()->id,
                    'appointmentDetailsDescription' => \Lang::get('common.appointment_details'),
                    'appointmentBookedDescription' => \Lang::get('common.booked'),
                    'availableDescription' => \Lang::get('common.available'),
                    'clickToMakeReservationDescription' => \Lang::get('common.click_to_make_reservation')
                ], 200, array(), true);
            }
        }
        
        return new JsonResponse(array(
            'type'    => 'error'        
        ));
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
    
    private function formatGraphicAndAppointments($graphicTime, $chosenDay, $chosenDayDateTime) 
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
                
                $appointmentId = 0;
                $ownAppointment = false;
                $bossWorkerAppointment = false;
                
                $explodedStartTime = explode(":", $startTime);
                $chosenDayDateTime->setTime($explodedStartTime[0], $explodedStartTime[1], 0);
                
                if ($appointment !== null)
                {
                    $boss = auth()->user();
                    $appointmentId = $appointment->id;
                    
                    if ($boss->isBoss !== null)
                    {       
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
                    
                    $ownAppointment = $appointment->user_id == $boss->id ? true : false;

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
                        'ownAppointment' => $ownAppointment,
                        'bossWorkerAppointment' => $bossWorkerAppointment,
                        'canMakeAnAppointment' => $chosenDayDateTime > $now ? true : false
                    ];
                    
                    $timeIncrementedByAppointmentMinutes = strtotime($appointment->end_time, strtotime($startTime));
                    $startTime = date('G:i', $timeIncrementedByAppointmentMinutes);
                    
                } else {
                    
                    $graphic[] = [
                        'time' => $startTime,
                        'appointment' => null,
                        'appointmentLimit' => 0,
                        'ownAppointment' => $ownAppointment,
                        'bossWorkerAppointment' => $bossWorkerAppointment,
                        'canMakeAnAppointment' => $chosenDayDateTime > $now ? true : false
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
    
    /**
     * Shows boss code
     */
    public function code()
    {
        $boss = auth()->user();
        $boss->load('code');
        
        return view('boss.code')->with([
            'code' => $boss->code
        ]);
    }
    
    /**
     * Sets registration code for workers
     * 
     * @param Request $request
     * @return type
     */
    public function setCode(Request $request)
    {
        $code = $request->request->get('code');
        $codeId = $request->request->get('code_id');
        
        if (is_string($code))
        {
            if ($code == "true")
            {
                $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $charactersLength = strlen($characters);
                $codeText = "";
                
                for ($i = 0; $i < 12; $i++) 
                {
                    $codeText .= $characters[rand(0, $charactersLength - 1)];
                }
                
                $message = 'Rejestracja pracowników została WŁĄCZONA';
                
            } else if ($code = "false") {
                
                $codeText = null;
                $message = 'Rejestracja pracowników została WYŁĄCZONA';
            }
            
            $code = Code::where('id', $codeId)->first();
            
            if ($code !== null)
            {
                $code->code = $codeText;
                $code->save();
                
                return redirect('/boss/code')->with('success', $message);
            }
        }

        return redirect()->route('welcome');
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function propertyEdit($id)
    {
        $property = Property::where([
            'id' => $id,
            'boss_id' => auth()->user()->id
        ])->first();
        
        if ($property !== null)
        {
            return view('boss.property_edit')->with('property', $property);
        }
        
        return redirect()->route('welcome');
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @return Response
     */
    public function propertyUpdate()
    {
        $rules = array(
            'property_id'   => 'required|numeric',
            'name'          => 'required|min:3',
            'street'        => 'required|min:3',
            'street_number' => 'required',
            'house_number'  => 'required',
            'city'          => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return Redirect::to('/boss/property/' . Input::get('property_id') . '/edit')
                ->withErrors($validator);
        } else {
            
            $boss = auth()->user();
            
            $property = Property::where('id', Input::get('property_id'))->first();
            $property->name          = Input::get('name');
            $property->slug          = str_slug(Input::get('name'));
            $property->street        = Input::get('street');
            $property->street_number = Input::get('street_number');
            $property->house_number  = Input::get('house_number');
            $property->city          = Input::get('city');
            $property->boss_id       = $boss->id;
            $property->save();

            return redirect('boss/subscription/list/' . $property->id . '/0')->with('success', 'Lokalizacja została zaktualizowana');
        }
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
        $property = Property::where('id', (int)$propertyId)->first();
        
        $subscriptionId = htmlentities((int)$subscriptionId, ENT_QUOTES, "UTF-8");
        $subscription = Subscription::where('id', (int)$subscriptionId)->with('items')->first();
            
        if ($property !== null && $subscription !== null)
        {
            $boss = User::where('id', auth()->user()->id)->with('chosenProperties')->first();

            if ($boss !== null && count($boss->chosenProperties) > 0)
            {
                $today = new \DateTime(date('Y-m-d'));
//                $today = date('Y-m-d', strtotime("+13 month", strtotime($today->format("Y-m-d"))));
                
                foreach ($boss->chosenProperties as $chosenProperty)
                {
                    if ($chosenProperty->property_id == $property->id)
                    {
                        $chosenProperty->load('purchases');

                        if (count($chosenProperty->purchases) > 0)
                        {
                            foreach ($chosenProperty->purchases as $purchase)
                            {
                                $purchase->load('subscription');
                                
                                if ($purchase->subscription !== null && $purchase->subscription->id == $subscription->id)
                                {
                                    $substart = Substart::where([
                                        'property_id' => $property->id,
                                        'subscription_id' => $subscription->id,
                                        'purchase_id' => $purchase->id
                                    ])->first();
                                    
                                    if ($substart !== null && $substart->start_date <= $today && $substart->end_date >= $today)
                                    {
                                        return redirect()->action(
                                            'BossController@subscriptionList', [
                                                'propertyId' => $property->id,
                                                'subscriptionId' => $subscription->id
                                            ]
                                        )->with('success', 'Posiadasz już te subskrypcje');
                                    }
                                }
                            }
                        }
                    }
                }
            }
            
            $formAction = 'BossController@subscriptionPurchased';
            
            $allSubstarts = Substart::where([
                'boss_id' => $boss->id,
                'property_id' => $property->id,
                'subscription_id' => $subscription->id
            ])->orderBy('id')->get();
            
            if (count($allSubstarts) > 0)
            {                
                if ($allSubstarts->last()->end_date < $today)
                {
                    $formAction = 'BossController@subscriptionPurchasedRefresh';
                }
            }

            return view('boss.subscription_purchase')->with([
                'property' => $property,
                'subscription' => $subscription,
                'formAction' => $formAction
            ]);
        }
        
        return redirect()->route('welcome');
    }
    
    /**
     * Subscription purchase method.
     * 
     * @return type
     */
    public function subscriptionPurchased()
    {
        $rules = array(
            'terms'             => 'required',
            'property_id'       => 'required',
            'subscription_id'   => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return Redirect::to('boss/subscription/purchase/' . Input::get('property_id') . '/' . Input::get('subscription_id'))
                ->withErrors($validator);
        } else {

            $subscription = Subscription::where('id', Input::get('subscription_id'))->first();
            $property = Property::where('id', Input::get('property_id'))->first();
            
            if ($subscription !== null && $property !== null)
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
                
                // >> check if such a subscription hasn't already been purchased!
                $isPurchasable = true;
                $boss = User::where('id', auth()->user()->id)->with('chosenProperties')->first();

                if (count($boss->chosenProperties) > 0)
                {
                    foreach ($boss->chosenProperties as $chosenProperty)
                    {
                        if ($chosenProperty->property_id == $property->id)
                        {
                            $chosenProperty = ChosenProperty::where('id', $chosenProperty->id)->with('purchases')->first();

                            if ($chosenProperty->purchases)
                            {
                                foreach ($chosenProperty->purchases as $purchase)
                                {
                                    if ($purchase->subscription_id == $subscription->id)
                                    {
                                        $isPurchasable = false;
                                    }
                                }
                            }
                        }
                    }
                }
                // <<

                if ($isPurchasable)
                {
                    $purchase = new Purchase();
                    $purchase->subscription_id = $subscription->id;
                    $purchase->chosen_property_id = $chosenProperty->id;
                    $purchase->save();
                    
                        $startDate = date('Y-m-d');

                        $substart = new Substart();
                        $substart->start_date = $startDate;
                        $endDate = date('Y-m-d', strtotime("+" . $subscription->duration . " month", strtotime($startDate)));
                        $endDate = date('Y-m-d', strtotime("-1 day", strtotime($endDate)));
                        $substart->end_date = $endDate;
                        $substart->boss_id = auth()->user()->id;
                        $substart->property_id = $property->id;
                        $substart->subscription_id = $subscription->id;
                        $substart->purchase_id = $purchase->id;
                        $substart->save();
                    
                    $purchase->substart_id = $substart->id;
                    $purchase->save();
                    
                    $chosenProperty->subscriptions()->attach($subscription);
                    $chosenProperty->save();

                    if ($purchase !== null && $substart !== null)
                    {                        
                        // >> create interval
                        $startDate = date('Y-m-d');
                                    
                        $interval = new Interval();
                        $interval->available_units = $subscription->quantity;
                        $interval->start_date = $startDate;
                        $startDate = date('Y-m-d', strtotime("+" . $subscription->duration . " month", strtotime($startDate)));

                        $endDate = date('Y-m-d', strtotime("-1 day", strtotime($startDate)));
                        $interval->end_date = $endDate;

                        $interval->substart_id = $substart->id;
                        $interval->purchase_id = $purchase->id;
                        $interval->save();
                        // <<

                        \Mail::to($boss)->send(new SubscriptionPurchased($boss, $subscription));

                        // redirect
                        return redirect()->action(
                            'BossController@subscriptionList', [
                                'propertyId' => $property->id,
                                'subscriptionId' => $subscription->id
                            ]
                        )->with('success', 'Subskrypcja dodana. Wiadomość z informacjami została wysłana na maila');
                    }
                }
            }
            
            return redirect()->route('welcome')->with('error', 'Niedozwolona próba');
        }
    }
    
    /**
     * Subscription purchase refresh method.
     * 
     * @return type
     */
    public function subscriptionPurchasedRefresh()
    {
        $rules = array(
            'terms'             => 'required',
            'property_id'       => 'required',
            'subscription_id'   => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return Redirect::to('boss/subscription/purchase/' . Input::get('property_id') . '/' . Input::get('subscription_id'))
                ->withErrors($validator);
        } else {

            $boss = User::where('id', auth()->user()->id)->with('chosenProperties')->first();
            
            $substarts = Substart::where([
                'boss_id' => $boss->id,
                'property_id' => Input::get('property_id'),
                'subscription_id' => Input::get('subscription_id')
            ])->orderBy('id')->get();
            
            if (count($substarts) > 0)
            {
                // dodaj sprawdzenie czy dziś jest już większe od ostatniego substartu



                dd($substarts);
            
            
            
            
            }
            
            return redirect()->route('welcome')->with('error', 'Niedozwolona próba');
        }
    }
    
    /**
     * Creates new code
     * 
     * @return type
     */
    public function addCode()
    {
        $code = new Code();
        $code->boss_id = auth()->user()->id;
        $code->save();
        
        if ($code->id !== null)
        {
            $type = 'success';
            $message = 'Dodano nowy kod.';
            
        } else {
            
            $type = 'error';
            $message = 'Błąd dodawania nowego kodu';
        }
        
        return redirect()->action(
            'BossController@code'
        )->with($type, $message);
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroyCode($id)
    {
        $code = Code::where('id', $id)->first();
        
        if ($code !== null)
        {     
            $code->delete();

            $type = 'success';
            $message = 'Kod został usunięty';
            
        } else {
            
            $type = 'error';
            $message = 'Podany kod nie istnieje';
        }
        
        return redirect()->action(
            'BossController@code'
        )->with($type, $message);
    }
    
    /**
     * Shows properties list with links to appointments views.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function propertyAppointments()
    {
        $boss = auth()->user()->load('properties');                
        
        if (count($boss->properties) == 1)
        {
            return redirect()->action(
                'BossController@workerAppointmentList', [
                    'propertyId' => $boss->properties->first()->id,
                ]
            );
        }

        return view('boss.property_appointments_index')->with([
            'properties' => $boss->properties
        ]);
    }
    
    /**
     * Shows a list of appointments assigned to given property.
     * 
     * @param type $propertyId
     * @param type $userId
     * 
     * @return type
     */
    public function workerAppointmentList($propertyId, $userId = 0)
    {
        $property = Property::where('id', $propertyId)->with('years.months')->first();
        
        $userId = htmlentities((int)$userId, ENT_QUOTES, "UTF-8");
        $userId = (int)$userId;
        
        if ($property !== null)
        {
            // >> get boss and its workers
            $boss = auth()->user();
            $boss->load([
                'appointments.day.month.year.property'
            ]);
                    
            if ($userId !== 0 && is_int($userId))
            {              
                if ($boss->id !== $userId)
                {
                    $worker = User::where([
                        'id' => $userId,
                        'boss_id' => $boss->id
                    ])->with([
                        'appointments.day.month.year.property'
                    ])->first();
                    
                } else if ($boss->id == $userId) {
                    
                    $worker = $boss;
                }
                
                $workers = new Collection();
                
                if ($worker !== null)
                {
                    $workers->push($worker);
                }
                
            } else {
                
                $workers = User::where('boss_id', $boss->id)->with([
                    'appointments.day.month.year.property'
                ])->get();
                $workers->prepend($boss);
            }
            // <<
            
            
            // >> get property calendar time intervals (in months)
            $propertyTimeIntervals = new Collection();
            $currentInterval = [];
            $today = new \DateTime(date('Y-m-d'));
//            $today = new \DateTime(date('Y-m-d', strtotime("+9 month", strtotime($today->format("Y-m-d")))));
            
            if (count($property->years) > 0)
            {
                foreach ($property->years as $year)
                {
                    if (count($year->months) > 0)
                    {
                        foreach ($year->months as $month)
                        {
                            // variables needed to check whether current month is current
                            $numberOfDaysInAGivenMonth = cal_days_in_month(CAL_GREGORIAN, $month->month_number, $year->year);
                            $givenMonthStartDateTime = new \DateTime(date($year->year . '-' . $month->month_number . '-' . 1));
                            $givenMonthEndDateTime = new \DateTime(date($year->year . '-' . $month->month_number . '-' . $numberOfDaysInAGivenMonth));
                            
                            $timeInterval = [
                                'start_date' => $givenMonthStartDateTime,
                                'end_date' => $givenMonthEndDateTime,
                                'month_id' => $month->id
                            ];
                            
                            if ($givenMonthStartDateTime <= $today && $today <= $givenMonthEndDateTime)
                            {
                                // set current interval
                                $currentInterval = $timeInterval;
                            }
                            
                            // add it to collection
                            $propertyTimeIntervals->push($timeInterval);
                        }
                    }
                }
            }
            
            // in case today is later than the last time interval
            if (count($currentInterval) == 0)
            {                
                $currentInterval = $propertyTimeIntervals->last();
            }
            // <<
            
            
            // >> get time interval appointments
            $appointmentsCollection = new Collection();
//            $currentInterval = new \DateTime(date('Y-m-d', strtotime("+1 month", strtotime($currentInterval->format("Y-m-d")))));
            
            if (count($workers) > 0)
            {
                foreach ($workers as $worker)
                {                    
                    if (count($worker->appointments) > 0)
                    {
                        foreach ($worker->appointments as $appointment)
                        {
                            if ($appointment->day->month->year->property->id == $property->id)
                            {
                                // variables needed to check whether appointments are in given month
                                $givenMonthStartDateTime = $currentInterval['start_date'];
                                $givenMonthEndDateTime = $currentInterval['end_date'];

                                if ($givenMonthStartDateTime <= $today && $today <= $givenMonthEndDateTime)
                                {
                                    $appointment['day'] = $appointment->day;
                                    $appointment['day_number'] = $appointment['day']->day_number;
                                    $appointment['month'] = $appointment->day->month;
                                    $appointment['month_number'] = $appointment['month']->month_number;
                                    $appointment['year'] = $appointment->day->month->year;

                                    $appointment['date'] = $appointment['day']->day_number. ' ' . $appointment['month']->month . ' ' . $appointment['year']->year;
                                    
                                    $monthNumber = (string)$appointment['month']->month_number;
                                    
                                    if (strlen($monthNumber) == 1)
                                    {
                                        $monthNumber = "0" . $appointment['month']->month_number;
                                    }
                                    
                                    $appointment['date_time'] = new \DateTime($appointment['year']->year. '-' . $monthNumber . '-' . $appointment['day']->day_number . ' ' . $appointment->start_time);

                                    $employee = User::where('id', $appointment->graphic->employee_id)->first();
                                    $appointment['employee_name'] = $employee->name . " " . $employee->surname;
                                    $appointment['employee_slug'] = $employee->slug;

                                    $appointment['user'] = $worker;
                                    $appointment['property'] = $property;

                                    $appointmentsCollection->push($appointment);
                                }
                            }
                        }
                    }
                }
            }
            
            $payments = [];
            
            if (count($currentInterval) > 0)
            {
                $payments = $this->countMonthlyPaymentsForDoneAppointments($currentInterval['month_id']);
            }
            
            $month = Month::where('id', $currentInterval['month_id'])->first();
            
            return view('boss.worker_appointment_list')->with([
                'appointments' => $appointmentsCollection->sortByDesc('date_time'),
                'worker' => $userId !== 0 ? $worker : null,
                'property' => $property,
                'currentInterval' => $currentInterval,
                'intervals' => $propertyTimeIntervals,
                'payments' => $payments,
                'month' => $month->month,
                'monthEn' => $month->month_en
            ]);
        }
        
        return redirect()->route('welcome');
    }
        
    /**
     * Shows boss worker.
     * 
     * 
     * 
     * 
     * todo: Czasowo usunięte, czeka aż powstanie widok z jakimiś dodatkowymi info których nie ma w worker_appointment_list??
     *
     * 
     * 
     * 
     * 
     * @param type $workerId
     * @param type $substartId
     * @param type $intervalId
     * 
     * @return \Illuminate\Contracts\Support\Renderable
     */
//    public function workerShow($workerId, $substartId, $intervalId)
//    {        
//        $boss = auth()->user();
//        
//        $worker = User::where([
//            'id' => $workerId
//        ])->first();
//        
//        if ($worker !== null)
//        {
//            if ($worker->isBoss !== null)
//            {
//                $worker = $boss;
//            }
//            
//            $substart = Substart::where([
//                'id' => $substartId
//            ])->first();
//            
//            if ($substart !== null)
//            {
//                if ($worker->isBoss !== null)
//                {
//                    $interval = Interval::where([
//                        'id' => $intervalId,
//                        'substart_id' => $substart->id
//                    ])->first();
//                    
//                } else {
//                    
//                    $interval = Interval::where([
//                        'id' => $intervalId
//                    ])->first();
//                }
//                
//                if ($interval !== null)
//                {
//                    $appointments = Appointment::where([
//                        'interval_id' => $interval->id,
//                        'user_id' => $worker->id
//                    ])->get();
//
//                    if (count($appointments) > 0)
//                    {
//                        foreach ($appointments as $appointment)
//                        {
//                            $day = Day::where('id', $appointment->day_id)->first();
//                            $month = Month::where('id', $day->month_id)->first();
//                            $year = Year::where('id', $month->year_id)->first();
//                            $calendar = Calendar::where('id', $year->calendar_id)->first();
//                            $employee = User::where('id', $calendar->employee_id)->first();
//
//                            $date = $day->day_number. ' ' . $month->month . ' ' . $year->year;
//                            $appointment['date'] = $date;
//
//                            $appointment['employee'] = $employee->name . " " . $employee->surname;
//                            $appointment['employee_slug'] = $employee->slug;
//                        }
//                    }
//                    
//                    $substartIntervals = Interval::where([
//                        'substart_id' => $substart->id
//                    ])->get();
//                    
//                    $subscription = Subscription::where('id', $substart->subscription_id)->first();
//                    
//                    return view('boss.worker_show')->with([
//                        'worker' => $worker,
//                        'substart' => $substart,
//                        'interval' => $interval,
//                        'substartIntervals' => $substartIntervals,
//                        'subscription' => $subscription,
//                        'appointments' => $appointments
//                    ]);
//                }
//            }
//        }
//        
//        return redirect()->route('welcome');
//    }
    
    public function subscriptionWorkersEdit($substartId, $intervalId)
    {
        $substart = Substart::where('id', $substartId)->first();
        
        if ($substart !== null)
        {
            $boss = auth()->user();
            
            if ($substart->boss_id === $boss->id)
            {
                $today = new \DateTime(date('Y-m-d'));
//                $today = date('Y-m-d', strtotime("+3 month", strtotime($today->format("Y-m-d"))));
                
                $substartIntervals = Interval::where('substart_id', $substart->id)->get();
                
                if (count($substartIntervals) > 0)
                {
                    foreach ($substartIntervals as $substartInterval)
                    {
                        $substartInterval['workers'] = User::where('boss_id', $boss->id)->get();
                        $substartInterval['isChecked'] = false;

                        if (count($substartInterval['workers']) > 0)
                        {
                            foreach ($substartInterval['workers'] as $worker)
                            {
                                $worker['withSubscription'] = false;
                            }
                        }
                    }
                    
                    $chosenInterval = Interval::where([
                        'id' => $intervalId,
                        'substart_id' => $substart->id
                    ])->first();
                    
                    if ($chosenInterval !== null)
                    {                                    
                        foreach ($substartIntervals as $substartInterval)
                        {
                            if ($chosenInterval->id == $substartInterval->id)
                            {
                                $substartInterval['isChecked'] = true;
                                $workersIntervals = Interval::withTrashed()->where('interval_id', $substartInterval->id)->get();

                                if (count($workersIntervals) > 0)
                                {
                                    foreach ($workersIntervals as $workerInterval)
                                    {
                                        $workerIntervalPurchase = Purchase::where('id', $workerInterval->purchase_id)->with('chosenProperty')->first();

                                        if ($workerIntervalPurchase && $workerIntervalPurchase->chosenProperty->user_id !== null)
                                        {
                                            $worker = User::where('id', $workerIntervalPurchase->chosenProperty->user_id)->first();
                                            
                                            if ($worker !== null)
                                            {
                                                foreach ($substartInterval['workers'] as $key => $intervalWorker)
                                                {
                                                    if ($intervalWorker->id == $worker->id)
                                                    {
                                                        $substartInterval['workers'][$key]['withSubscription'] = $workerInterval->deleted_at == null ? true : false;
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                                
                                $chosenInterval = $substartInterval;
                            }
                        }
                        
                    } else {
                        
                        foreach ($substartIntervals as $substartInterval)
                        {
                            if ($substartInterval->start_date <= $today && $substartInterval->end_date >= $today)
                            {
                                $workersIntervals = Interval::withTrashed()->where('interval_id', $substartInterval->id)->get();
                                                                                        
                                if (count($workersIntervals) > 0)
                                {
                                    foreach ($workersIntervals as $workerInterval)
                                    {
                                        $workerIntervalPurchase = Purchase::where('id', $workerInterval->purchase_id)->with('chosenProperty')->first();
                                        
                                        if ($workerIntervalPurchase !== null && $workerIntervalPurchase->chosenProperty->user_id !== null)
                                        {                                            
                                            $worker = User::where('id', $workerIntervalPurchase->chosenProperty->user_id)->first();
                                            
                                            if ($worker !== null)
                                            {
                                                foreach ($substartInterval['workers'] as $key => $intervalWorker)
                                                {
                                                    if ($intervalWorker->id == $worker->id)
                                                    {
                                                        $substartInterval['workers'][$key]['withSubscription'] = $workerInterval->deleted_at == null ? true : false;
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                    
                    $hasIntervalBeenChosen = false;
                                        
                    foreach ($substartIntervals as $substartInterval)
                    {
                        if ($substartInterval['isChecked'] == true)
                        {
                            $hasIntervalBeenChosen = true;
                            break;
                        }
                    }
                    
                    if ($hasIntervalBeenChosen == false)
                    {
                        foreach ($substartIntervals as $substartInterval)
                        {
                            if ($substartInterval->start_date <= $today && $substartInterval->end_date >= $today)
                            {
                                $substartInterval['isChecked'] = true;
                                $hasIntervalBeenChosen = true;
                                
                                if ($chosenInterval == null)
                                {
                                    $chosenInterval = $substartInterval;
                                }
                                
                                break;
                            }
                        }
                        
                        if ($hasIntervalBeenChosen == false)
                        {
                            $chosenInterval = $substartIntervals->last();
                            $substartIntervals->last()['isChecked'] = true;
                        }
                    }
                }
                
                return view('boss.subscription_workers_edit')->with([
                    'substart' => $substart,
                    'subscription' => Subscription::where('id', $substart->subscription_id)->first(),
                    'substartIntervals' => $substartIntervals,
                    'chosenInterval' => $chosenInterval,
                    'today' => $today
                ]);
            }
            
            return redirect()->route('welcome')->with('error', 'Wykupiona subskrypcja ma innego właściciela');
        }
        
        return redirect()->route('welcome')->with('error', 'Wykupiona subskrypcja o podanym id, nie istnieje');
    }
    
    public function subscriptionWorkersUpdate()
    {
        $rules = array(
            'substart_id' => 'required|numeric',
            'interval_id' => 'required|numeric',
            'workers_on'  => 'sometimes',
            'workers_off' => 'sometimes'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails())
        {
            return Redirect::to('boss/subscription/workers/edit/' . Input::get('substart_id') . '/' . Input::get('interval_id'));
            
        } else {
            
            $boss = auth()->user();
            
            if ($boss->isBoss == 1)
            {
                $substart = Substart::where([
                    'id' => Input::get('substart_id'),
                    'boss_id' => $boss->id
                ])->first();
                
                $subscription = Subscription::where('id', $substart->subscription_id)->first();
                $property = Property::where('id', $substart->property_id)->first();

                if ($substart !== null && $subscription !== null && $property !== null)
                {
                    $today = new \DateTime(date('Y-m-d'));
                    
                    $chosenInterval = Interval::where([
                        'id' => Input::get('interval_id'),
                        'substart_id' => $substart->id
                    ])->first();
                    
                    if ($chosenInterval !== null && !($chosenInterval->start_date < $today && $chosenInterval->end_date < $today))
                    {
                        if (Input::get('workers_on') !== null && count(Input::get('workers_on')) > 0)
                        {
                            $workersOn = new Collection();
                        
                            foreach (Input::get('workers_on') as $workerId)
                            {
                                $worker = User::where([
                                    'id' => $workerId,
                                    'boss_id' => $boss->id
                                ])->first();

                                if ($worker !== null)
                                {
                                    $workersOn->push($worker);
                                }
                            }

                            if (count($workersOn) > 0)
                            {
                                // turn subscription on
                                foreach ($workersOn as $worker)
                                {
                                    $workerChosenProperty = ChosenProperty::where([
                                        'user_id' => $worker->id,
                                        'property_id' => $property->id
                                    ])->first();
                                    
                                    if ($workerChosenProperty === null)
                                    {
                                        $workerChosenProperty = new ChosenProperty();
                                        $workerChosenProperty->user_id = $worker->id;
                                        $workerChosenProperty->property_id = $property->id;
                                        $workerChosenProperty->save();
                                    }
                                    
                                    $workerPurchase = Purchase::where([
                                        'substart_id' => $substart->id,
                                        'subscription_id' => $subscription->id,
                                        'chosen_property_id' => $workerChosenProperty->id
                                    ])->first();
                                    
                                    if ($workerPurchase === null)
                                    {
                                        $workerPurchase = new Purchase();
                                        $workerPurchase->substart_id = $substart->id;
                                        $workerPurchase->subscription_id = $subscription->id;
                                        $workerPurchase->chosen_property_id = $workerChosenProperty->id;
                                        $workerPurchase->save();
                                    }
                                    
                                    if ($workerPurchase !== null)
                                    {
                                        $bossIntervals = Interval::where('substart_id', $substart->id)->get();
                                        
                                        if (count($bossIntervals) > 0)
                                        {
                                            if (count($bossIntervals) == 1)
                                            {
                                                $bossInterval = Interval::where('id', $bossIntervals->first()->id)->first();
                                                        
                                                $workerInterval = Interval::withTrashed()->where([
                                                    'interval_id' => $bossInterval->id,
                                                    'purchase_id' => $workerPurchase->id
                                                ])->first();
                                                
                                                if ($workerInterval == null)
                                                {
                                                    $workerInterval = new Interval();
                                                    $workerInterval->available_units = $subscription->quantity;
                                                    $workerInterval->start_date = $substart->start_date;
                                                    $workerInterval->end_date = $substart->end_date;
                                                    $workerInterval->interval_id = $bossIntervals->first()->id;
                                                    $workerInterval->purchase_id = $workerPurchase->id;
                                                    $workerInterval->save();
                                                    
                                                } else {
                                                    
                                                    $workerInterval->deleted_at = null;
                                                    $workerInterval->save();
                                                }
                                                
                                                $bossInterval->workers_available_units = $bossInterval->workers_available_units + $subscription->quantity;
                                                $bossInterval->save();
                                                
                                            } else if (count($bossIntervals) > 1) {
                                                
                                                foreach ($bossIntervals as $bossInterval)
                                                {
                                                    if ($bossInterval->start_date <= $chosenInterval->start_date && $bossInterval->end_date >= $chosenInterval->end_date ||
                                                        $bossInterval->start_date >= $chosenInterval->start_date)
                                                    {
                                                        $workerInterval = Interval::withTrashed()->where([
                                                            'interval_id' => $bossInterval->id,
                                                            'purchase_id' => $workerPurchase->id
                                                        ])->first();
                                                        
                                                        if ($workerInterval == null)
                                                        {
                                                            $workerInterval = new Interval();
                                                            $workerInterval->available_units = $subscription->quantity;
                                                            $workerInterval->start_date = $bossInterval->start_date;
                                                            $workerInterval->end_date = $bossInterval->end_date;
                                                            $workerInterval->interval_id = $bossInterval->id;
                                                            $workerInterval->purchase_id = $workerPurchase->id;
                                                            $workerInterval->save();
                                                            
                                                        } else {
                                                            
                                                            $workerInterval->deleted_at = null;
                                                            $workerInterval->save();
                                                        }
                                                        
                                                        $bossInterval->workers_available_units = $bossInterval->workers_available_units + $subscription->quantity;
                                                        $bossInterval->save();
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        
                        if (Input::get('workers_off') !== null && count(Input::get('workers_off')) > 0)
                        {
                            $workersOff = new Collection();
                        
                            foreach (Input::get('workers_off') as $workerId)
                            {
                                $worker = User::where([
                                    'id' => $workerId,
                                    'boss_id' => $boss->id
                                ])->with('chosenProperties')->first();

                                if ($worker !== null)
                                {
                                    $workersOff->push($worker);
                                }
                            }
                            
                            if (count($workersOff) > 0)
                            {
                                // turn subscription off
                                foreach ($workersOff as $worker)
                                {
                                    $workerChosenProperty = ChosenProperty::where([
                                        'user_id' => $worker->id,
                                        'property_id' => $property->id
                                    ])->first();
                                    
                                    if ($workerChosenProperty !== null)
                                    {
                                        $workerPurchase = Purchase::where([
                                            'substart_id' => $substart->id,
                                            'subscription_id' => $subscription->id,
                                            'chosen_property_id' => $workerChosenProperty->id
                                        ])->first();

                                        if ($workerPurchase !== null)
                                        {
                                            $workerIntervals = Interval::where([
                                                'purchase_id' => $workerPurchase->id
                                            ])->get();
                                            
                                            if (count($workerIntervals) > 0)
                                            {
                                                foreach ($workerIntervals as $workerInterval)
                                                {
                                                    if ($workerInterval->start_date <= $chosenInterval->start_date && $workerInterval->end_date >= $chosenInterval->end_date ||
                                                        $workerInterval->start_date >= $chosenInterval->start_date)
                                                    {
                                                        $bossInterval = Interval::where('id', $workerInterval->interval_id)->first();
                                                        
                                                        $intervalAppointments = Appointment::where([
                                                            'user_id' => $worker->id,
                                                            'interval_id' => $workerInterval->id,
                                                            'purchase_id' => $workerPurchase->id
                                                        ])->get();
                                                        
                                                        if (count($intervalAppointments) > 0)
                                                        {
                                                            foreach ($intervalAppointments as $intervalAppointment)
                                                            {
                                                                if ($intervalAppointment->status == 0) 
                                                                {
                                                                    $intervalAppointment->delete();
                                                                    
                                                                    $workerInterval->available_units = $workerInterval->available_units + 1;
                                                                    $workerInterval->save();
                                                                    
                                                                    $bossInterval->workers_available_units = $bossInterval->workers_available_units + 1;
                                                                    $bossInterval->save();
                                                                }
                                                            }
                                                        }
                                                        
                                                        $workerInterval->delete();
                                                        
                                                        $bossInterval->workers_available_units = $bossInterval->workers_available_units - $subscription->quantity;
                                                        $bossInterval->save();
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        
                        return redirect('/boss/subscription/workers/edit/' . $substart->id . '/' . $chosenInterval->id)->with('success', 'Zmiany zostały wykonane');
                    }
                }
            }
            
            return redirect()->route('welcome')->with('error', 'Coś poszło nie tak');
        }
    }
    
    public function subscriptionInvoices($substartId)
    {
        $substart = Substart::where('id', $substartId)->first();
        
        if ($substart !== null)
        {
            $boss = auth()->user();
            
            if ($substart->boss_id === $boss->id)
            {
                $invoiceData = InvoiceData::where([
                    'property_id' => $substart->property_id,
                    'owner_id' => $boss->id
                ])->first();
                
                if ($invoiceData === null)
                {
                    return redirect()->action(
                        'BossController@invoiceDataCreate', [
                            'substartId' => $substart->id
                        ]
                    );
                }
        
                $subscription = Subscription::where('id', $substart->subscription_id)->first();
                $substartIntervals = Interval::where('substart_id', $substart->id)->get();
                
                $today = new \DateTime(date('Y-m-d'));
                //$today = date('Y-m-d', strtotime("+1 month", strtotime($today->format("Y-m-d"))));
                
                foreach ($substartIntervals as $interval)
                {
                    if ($today > $interval->start_date && $today >= $interval->end_date) 
                    {
                        $interval['state'] = 'existing';
                        
                    } elseif ($today < $interval->start_date || $today < $interval->end_date) {
                        
                        $interval['state'] = 'nonexistent';
                    }
                }
                                
                return view('boss.subscription_invoices')->with([
                    'invoiceData' => $invoiceData,
                    'substart' => $substart,
                    'subscription' => $subscription,
                    'intervals' => $substartIntervals
                ]);
            }
            
            return redirect()->route('welcome')->with('error', 'Wykupiona subskrypcja ma innego właściciela');
        }
        
        return redirect()->route('welcome')->with('error', 'Wykupiona subskrypcja o podanym id, nie istnieje');
    }
    
    public function invoiceDataCreate($substartId) 
    {
        $substart = Substart::where([
            'id' => $substartId,
            'boss_id' => auth()->user()->id
        ])->first();
        
        if ($substart !== null)
        { 
            $property = Property::where('id', $substart->property_id)->first();
            
            return view('boss.property_invoice_data')->with([
                'property' => $property,
                'substart' => $substart
            ]);
        }
        
        return redirect()->route('welcome')->with('error', 'Podana subskrypcja nie istnieje lub ma innego właściciela');
    }
    
    public function invoiceDataStore() 
    {
        $rules = array(
            'company_name'   => 'required|string|min:2|max:45',
            'email'          => 'required|email|unique:invoice_datas|max:33',
            'phone_number'   => 'numeric|regex:/[0-9]/|min:7',
            'nip'            => 'required|min:10',
//            'bank_name'      => 'required|string',
//            'account_number' => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return Redirect::to('boss/subscription/invoice/create/' . Input::get('substart_id'))
                ->withErrors($validator);
        } else {
            
            $boss = auth()->user();
            
            $substart = Substart::where([
                'id' => Input::get('substart_id'),
                'boss_id' => $boss->id
            ])->first();
            
            if ($substart !== null)
            {
                $property = Property::where([
                    'id' => $substart->property_id,
                    'boss_id' => $boss->id
                ])->first();
                
                if ($property !== null)
                {
                    $invoiceData = new InvoiceData();
                    $invoiceData->company_name    = Input::get('company_name');
                    $invoiceData->email           = Input::get('email');
                    $invoiceData->phone_number    = Input::get('phone_number');
                    $invoiceData->nip             = Input::get('nip');
//                    $invoiceData->bank_name       = Input::get('bank_name');
//                    $invoiceData->account_number  = Input::get('account_number');
                    $invoiceData->owner_id        = $boss->id;           
                    $invoiceData->property_id     = $property->id;           
                    $invoiceData->save();

                    return redirect('boss/subscription/invoices/' . $substart->id)->with('success', 'Dane do faktury zostały uzupełnione!');
                }
            }
            
            return redirect()->route('welcome')->with('error', 'Niepoprawne dane');
        }
    }
    
    /**
     * Show the form for editing the specified resource.
     * 
     * @param type $invoiceDataId
     * @param type $substartId
     * 
     * @return type
     */
    public function invoiceDataEdit($invoiceDataId, $substartId)
    {
        $boss = auth()->user();
        
        $invoiceData = InvoiceData::where('id', $invoiceDataId)->first();
        $substart = Substart::where([
            'id' => $substartId,
            'boss_id' => $boss->id
        ])->first();
        
        if ($invoiceData !== null && $substart !== null && 
            $invoiceData->property_id == $substart->property_id && $invoiceData->owner_id == $substart->boss_id)
        {
            return view('boss.property_invoice_data_edit')->with([
                'invoiceData' => $invoiceData,
                'substart' => $substart
            ]);
        }
        
        return redirect()->route('welcome')->with('error', 'Dane do faktury nie istnieją');
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @return Response
     */
    public function invoiceDataUpdate(Request $request)
    {
        $invoiceDataId = htmlentities($request->get('invoice_data_id'), ENT_QUOTES, "UTF-8");
        $substartId = htmlentities($request->get('substart_id'), ENT_QUOTES, "UTF-8");
        $companyName = htmlentities($request->get('company_name'), ENT_QUOTES, "UTF-8");
        $email = htmlentities($request->get('email'), ENT_QUOTES, "UTF-8");
        $phoneNumber = htmlentities($request->get('phone_number'), ENT_QUOTES, "UTF-8");
        $nip = htmlentities($request->get('nip'), ENT_QUOTES, "UTF-8");
//        $bankName = htmlentities($request->get('bank_name'), ENT_QUOTES, "UTF-8");
//        $accountNumber = htmlentities($request->get('account_number'), ENT_QUOTES, "UTF-8");
        
        if ($invoiceDataId !== null && 
            $companyName !== null &&
            $email !== null &&
            $phoneNumber !== null &&
            $nip !== null)
                
//            $bankName !== null &&
//            $accountNumber !== null)
            
        {
            $boss = auth()->user();
            
            $substart = Substart::where([
                'id' => $substartId,
                'boss_id' => $boss->id
            ])->first();
            
            if ($substart !== null)
            {
                $invoiceData = InvoiceData::where([
                    'id' => $invoiceDataId,
                    'owner_id' => $boss->id,
                    'property_id' => $substart->property_id
                ])->first();
                
                if ($invoiceData !== null)
                {
                    $invoiceData->company_name    = $companyName;
                    $invoiceData->email           = $email;
                    $invoiceData->phone_number     = $phoneNumber;
                    $invoiceData->nip             = $nip;
//                    $invoiceData->bank_name       = $bankName;
//                    $invoiceData->account_number  = $accountNumber;       
                    $invoiceData->save();

                    return redirect('boss/subscription/invoices/' . $substart->id)->with('success', 'Dane do faktury zostały zmienione!');
                }
            }
        }
            
        return redirect()->route('welcome')->with('error', 'Nieprawidłowe dane do faktury');
    }
    
    public function subscriptionInvoice($intervalId) 
    {
        $interval = Interval::where('id', $intervalId)->first();
        
        if ($interval !== null)
        {
            $boss = auth()->user();
            $substart = Substart::where('id', $interval->substart_id)->first();
            
            if ($substart !== null && $substart->boss_id === $boss->id)
            {            
                $admin = User::where([
                    'isAdmin' => 1
                ])->first();
                
                if ($admin !== null)
                {
                    $adminInvoiceData = InvoiceData::withTrashed()->where([
                        'owner_id' => $admin->id
                    ])->first();
                    
                    $bossInvoiceData = InvoiceData::where([
                        'owner_id' => $boss->id,
                        'property_id' => $substart->property_id
                    ])->first();
                    
                    $bossProperty = Property::where('id', $substart->property_id)->first();
                    
                    $workersIntervals = Interval::where('interval_id', $interval->id)->get();
                    $workersIntervals->push($interval);
                    
                    $intervalWorkersCount = 0;
                    
                    foreach ($workersIntervals as $interval)
                    {  
                        $intervalAppointments = Appointment::where('interval_id', $interval->id)->get();
                        
                        if (count($intervalAppointments) > 0)
                        {
                            foreach ($intervalAppointments as $appointment)
                            {
                                if ($appointment->status == 1)
                                {
                                    $intervalWorkersCount++;
                                    break;
                                }
                            }
                        }
                    }
                    
                    $VAT = 23;
                    $VATMultiplier = (1  - ($VAT / 100));
                    
                    $subscription = Subscription::where('id', $substart->subscription_id)->first();
                    $subscriptionSingleNetPrice = $subscription->old_price * $VATMultiplier;
                    $subscriptionSingleNetPriceAfterDiscount = $subscription->new_price * $VATMultiplier;
                    $subscriptionSingleDiscount = 100 - (($subscriptionSingleNetPriceAfterDiscount * 100) / $subscriptionSingleNetPrice);
                    
                    $subscriptionAllNetPrice = $subscriptionSingleNetPriceAfterDiscount * $intervalWorkersCount;
                    $subscriptionAllGrossPrice = $subscription->new_price * $intervalWorkersCount;
                    $theAmountOfVAT = $subscriptionAllGrossPrice - $subscriptionAllNetPrice;
                            
                    if ($adminInvoiceData !== null && $bossInvoiceData !== null)
                    {
                        $pdf = \PDF::loadView('invoices.subscription_monthly_pay', [
                            'adminInvoiceData' => $adminInvoiceData,
                            'bossInvoiceData' => $bossInvoiceData,
                            'bossProperty' => $bossProperty,
                            'subscription' => $subscription,
                            'intervalWorkersCount' => $intervalWorkersCount,
                            'subscriptionSingleNetPrice' => $subscriptionSingleNetPrice,
                            'subscriptionSingleDiscount' => $subscriptionSingleDiscount,
                            'subscriptionSingleNetPriceAfterDiscount' => $subscriptionSingleNetPriceAfterDiscount,
                            'VAT' => $VAT,
                            'subscriptionAllNetPrice' => $subscriptionAllNetPrice,
                            'theAmountOfVAT' => $theAmountOfVAT,
                            'subscriptionAllGrossPrice' => $subscriptionAllGrossPrice,
                            'substart' => $substart,
                            'interval' => $interval,
                            'intervalWorkersCount' => $intervalWorkersCount
                        ]);
                        
                        return $pdf->download('faktura_za_' . $interval->start_date->format("Y-m-d") . '-' . $interval->end_date->format("Y-m-d") . ' ' . config('app.name') . '.pdf');
                    }
                }
            }
            
            return redirect()->route('welcome')->with('error', 'Faktura należy do kogoś innego');
        }
        
        return redirect()->route('welcome')->with('error', 'Faktura nie istnieje');
    }
    
    /**
     * Method to send graphic request to admin.
     * 
     * @param Request $request
     * 
     * @return type
     */
    public function makeAGraphicRequest()
    {
        $rules = array(
            'start_time' => 'required',
            'end_time'   => 'required',
            'employees'  => 'required',
            'day'        => 'required|numeric'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails())
        {
            return Redirect::to('/')->withErrors($validator);
            
        } else {
            
            $boss = auth()->user();
            
            if ($boss->isBoss == 1)
            {
                $day = Day::where('id', Input::get('day'))->with('month.year.property')->first();
                
                if ($day !== null && $day->month->year->property->boss_id == $boss->id)
                {          
                    $graphicRequest = new GraphicRequest();
                    $graphicRequest->start_time = Input::get('start_time');
                    $graphicRequest->end_time = Input::get('end_time');
                    $graphicRequest->comment = Input::get('comment');
                    $graphicRequest->property_id = $day->month->year->property->id;
                    $graphicRequest->day_id = $day->id;
                    $graphicRequest->save();

                    if ($graphicRequest !== null)
                    {
                        foreach (Input::get('employees') as $employee_id)
                        {
                            $employee = User::where([
                                'id' => $employee_id,
                                'isEmployee' => 1
                            ])->first();

                            if ($employee !== null)
                            {
                                $graphicRequest->employees()->attach($employee->id);
                                $graphicRequest->save();
                            }
                        }

                        return redirect()->action(
                            'BossController@graphicRequests'
                        )->with('success', 'Zapytanie o otwarcie grafiku zostało wysłane');
                    }        
                }
            }
            
            return redirect()->route('welcome')->with('error', 'Coś poszło nie tak');
        }
    }
    
    public function graphicRequests()
    {
        $boss = User::where('id', auth()->user()->id)->with([
            'properties.graphicRequests.day.month.year'
        ])->first();
        
        $graphicRequests = new Collection();
        
        if (count($boss->properties) > 0)
        {
            foreach ($boss->properties as $property)
            {
                if (count($property->graphicRequests) > 0)
                {
                    foreach ($property->graphicRequests as $graphicRequest)
                    {
                        $graphicRequest->load([
                            'property',
                            'employees'
                        ]);
                        $graphicRequests->push($graphicRequest);
                    }
                }
            }
        }
        
        return view('boss.graphic_requests')->with([
            'graphicRequests' => $graphicRequests
        ]);
    }
    
    public function graphicRequestShow($graphicRequestId)
    {
        $boss = auth()->user();
        
        $graphicRequest = GraphicRequest::where('id', $graphicRequestId)->with([
            'property.boss',
            'day.month.year',
            'employees',
            'messages.user'
        ])->first();
        
        if ($graphicRequest !== null && $graphicRequest->property->boss_id == $boss->id)
        {
            $allEmployees = User::where('isEmployee', 1)->get();
            
            foreach ($allEmployees as $employee)
            {
                foreach ($graphicRequest->employees as $chosenEmployee)
                {
                    if ($employee->id == $chosenEmployee->id)
                    {
                        $employee['isChosen'] = true;
                    }
                }
            }
            
            $graphicRequest['allEmployees'] = $allEmployees;
            $graphicRequest['boss'] = $graphicRequest->property->boss;
            
            return view('boss.graphic_request')->with([
                'graphicRequest' => $graphicRequest,
                'boss' => $boss
            ]);
        }
        
        return redirect()->route('welcome')->with('error', 'Podane zapytanie o grafik nie istnieję lub ma innego właściciela');
    }
    
    public function graphicRequestEdit($graphicRequestId)
    {
        $boss = auth()->user();
        
        $graphicRequest = GraphicRequest::where([
            'id' => $graphicRequestId,
            'boss_id' => $boss->id
        ])->with('employees')->first();
        
        if ($graphicRequest !== null)
        {
            $allEmployees = User::where('isEmployee', 1)->get();
            
            foreach ($allEmployees as $employee)
            {
                foreach ($graphicRequest->employees as $chosenEmployee)
                {
                    if ($employee->id == $chosenEmployee->id)
                    {
                        $employee['isChosen'] = true;
                    }
                }
            }
            
            $graphicRequest['allEmployees'] = $allEmployees;
            
            return view('boss.graphic_request_edit')->with([
                'graphicRequest' => $graphicRequest
            ]);
        }
        
        return redirect()->route('welcome')->with('error', 'Podane zapytanie o grafik nie istnieję lub ma innego właściciela');
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @return Response
     */
    public function graphicRequestUpdate(Request $request)
    {        
        $graphicRequestId = htmlentities($request->get('graphic_request_id'), ENT_QUOTES, "UTF-8");
        $startTime= htmlentities($request->get('start_time'), ENT_QUOTES, "UTF-8");
        $endTime = htmlentities($request->get('end_time'), ENT_QUOTES, "UTF-8");
        $comment = htmlentities($request->get('comment'), ENT_QUOTES, "UTF-8");
        
        $employees = [];
        $isEmployeesArrayValid = true;
        
        foreach ($request->get('employees') as $employee)
        {
            $employee = User::where([
                'id' => htmlentities((int)$employee, ENT_QUOTES, "UTF-8"),
                'isEmployee' => 1
            ])->first();
            
            if ($employee !== null)
            {
                $employees[] = $employee;
            }
            
            if (!($employee instanceof \App\User)) 
            {
                $isEmployeesArrayValid = false;
            }
        }
        
        if ($graphicRequestId !== null &&
            $startTime !== null &&
            $endTime !== null &&
            $comment !== null &&
            is_array($employees) &&
            $isEmployeesArrayValid)
        {
            $boss = auth()->user();
            
            $graphicRequest = GraphicRequest::where([
                'id' => $graphicRequestId,
                'boss_id' => $boss->id
            ])->first();
            
            if ($graphicRequest !== null)
            {
                $graphicRequest->start_time  = $startTime;
                $graphicRequest->end_time    = $endTime;
                $graphicRequest->comment     = $comment;
                
                $graphicRequest->employees()->detach();
                
                foreach ($employees as $employee)
                {
                    $graphicRequest->employees()->attach($employee->id);
                }
                    
                $graphicRequest->updated_at = new \DateTime();
                $graphicRequest->save();

                return redirect('/boss/graphic-request/' . $graphicRequest->id)->with('success', 'Zapytanie o otworzenie grafiku zostało zmienione!');
            }
        }
            
        return redirect()->route('welcome')->with('error', 'Nieprawidłowe dane do faktury');
    }
    
    public function approveMessages()
    {
        $promoCode = PromoCode::where('boss_id', auth()->user()->id)->with([
            'promo',
            'boss',
            'messages'
        ])->first();
        
        if ($promoCode !== null)
        {
            return view('boss.approve_messages')->with([
                'promoCode' => $promoCode
            ]);
        }
        
        return redirect()->route('welcome')->with('error');
    }
    
    public function makeAMessage()
    {
        $rules = array(
            'text'               => 'required|string',
            'graphic_request_id' => 'required|numeric'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails())
        {
            return Redirect::to('boss/graphic-request/' . Input::get('graphic_request_id'));
            
        } else {
            
            $boss = auth()->user();
            
            if ($boss->isBoss == 1)
            {
                $graphicRequest = GraphicRequest::where([
                    'id' => Input::get('graphic_request_id')
                ])->with('property')->first();

                if ($graphicRequest !== null && $graphicRequest->property !== null && $graphicRequest->property->boss_id == $boss->id)
                {
                    $message = new Message();
                    $message->text = Input::get('text');
                    $message->status = 0;
                    $message->user_id = $boss->id;
                    $message->graphic_request_id = $graphicRequest->id;
                    $message->save();
                    
                    return redirect('/boss/graphic-request/' . $graphicRequest->id)->with('success', 'Wiadomość została wysłana!');
                }
            }
            
            return redirect()->route('welcome')->with('error', 'Coś poszło nie tak');
        }
    }
    
    public function makeAnApproveMessage()
    {
        $rules = array(
            'text'          => 'required|string',
            'promo_code_id' => 'required|numeric'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails())
        {
            return Redirect::to('boss/approve/messages');
            
        } else {
            
            $boss = auth()->user();
            
            if ($boss->isApproved == 0)
            {
                $promoCode = PromoCode::where([
                    'id' => Input::get('promo_code_id'),
                    'boss_id' => $boss->id
                ])->first();

                if ($promoCode !== null)
                {
                    $message = new Message();
                    $message->text = Input::get('text');
                    $message->status = 0;
                    $message->user_id = $promoCode->boss_id;
                    $message->promo_code_id = $promoCode->id;
                    $message->save();
                    
                    return redirect('/boss/approve/messages')->with('success', 'Wiadomość została wysłana!');
                }
            }
            
            return redirect()->route('welcome')->with('error', 'Coś poszło nie tak');
        }
    }    
    
    private function getWorkersFrom($substartId)
    {
        $substart = Substart::where('id', $substartId)->first();

        if ($substart !== null)
        {
            $boss = User::where([
                'id' => auth()->user()->id,
                'isBoss' => 1
            ])->with('chosenProperties')->first();
            
            $workers = User::where('boss_id', $boss->id)->with('chosenProperties')->get();
            $workersCollection = new Collection();

            foreach ($workers as $worker)
            {
                if (count($worker->chosenProperties) > 0)
                {
                    foreach ($worker->chosenProperties as $chosenProperty)
                    {
                        if ($chosenProperty->property_id == $substart->property_id)
                        {
                            $chosenProperty = ChosenProperty::where('id', $chosenProperty->id)->with('purchases')->first();

                            if (count($chosenProperty->purchases) > 0)
                            {
                                foreach($chosenProperty->purchases as $purchase)
                                {
                                    if ($purchase->subscription_id == $substart->subscription_id && $purchase->substart_id == $substart->id)
                                    {
                                        $interval = Interval::where('purchase_id', $purchase->id)->get();
                                        
                                        if (count($interval) > 0)
                                        {
                                            $workersCollection->push($worker);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            
            if (count($boss->chosenProperties) > 0)
            {
                foreach ($boss->chosenProperties as $chosenProperty)
                {
                    if ($chosenProperty->property_id == $substart->property_id)
                    {
                        $chosenProperty = ChosenProperty::where('id', $chosenProperty->id)->with('purchases')->first();

                        if (count($chosenProperty->purchases) > 0)
                        {
                            foreach($chosenProperty->purchases as $purchase)
                            {
                                if ($purchase->subscription_id == $substart->subscription_id && $purchase->substart_id == $substart->id)
                                {
                                    $workersCollection->push($boss);
                                }
                            }
                        }
                    }
                }
            }

            $workersArr = [];

            foreach ($workersCollection as $workerCollection)
            {
                $workersArr[] = [
                    'id' => $workerCollection->id,
                    'name' => $workerCollection->name,
                    'surname' => $workerCollection->surname,
                    'email' => $workerCollection->email,
                    'phone_number' => $workerCollection->phone_number,
                    'workers_appointment_show_button' => route('workerAppointmentList', [
                        'substartId' => $substart->id,
                        'userId' => $workerCollection->id
                    ]),
                ];
            }
            
            return $workersArr;
        }
    }
    
    public function getPropertyUsersFromDatabase(Request $request)
    {        
        if ($request->get('searchField') && $request->get('propertyId'))
        {
            $searchField = htmlentities($request->get('searchField'), ENT_QUOTES, "UTF-8");
                    
            $boss = auth()->user();
            $property = Property::where('id', $request->get('propertyId'))->first();

            if ($property !== null && $property->boss_id == $boss->id)
            {                   
                // >> look for users within boss workers
                $users = User::where([
                    ['name', 'like', $searchField . '%'],
                    ['boss_id', $boss->id]
                ])->orWhere([
                    ['surname', 'like', $searchField . '%'],
                    ['boss_id', $boss->id]
                ])->get();
                // <<

                // >> next, look for boss entity
                $bossSearchedEntity = User::where([
                    ['id', $boss->id],
                    ['name', 'like', $searchField . '%'],
                    ['isBoss', 1]
                ])->orWhere([
                    ['id', $boss->id],
                    ['surname', 'like', $searchField . '%'],
                    ['isBoss', 1]
                ])->first();
                // <<

                // >> if searched boss entity exist, add it to users collection
                if ($bossSearchedEntity !== null)
                {            
                    $users->push($bossSearchedEntity);
                }
                // <<

                $data = [
                    'type'    => 'success',
                    'users'  => $users
                ];

                return new JsonResponse($data, 200, array(), true);
            }
        }
        
        return new JsonResponse(array(
            'type'    => 'error',
            'message' => 'Pusty request'            
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
                'button' => route('subscriptionInvoices', [
                    'substartId' => $substarts->id
                ]),
                'button_description' => \Lang::get('common.invoices'),
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
                    'button' => route('subscriptionInvoices', [
                        'substartId' => $substart->id
                    ]),
                    'button_description' => \Lang::get('common.invoices'),
                    'isActiveMessage' => $isActiveMessage,
                    'isActive' => $substart->isActive
                ];
            }
        }
        
        return $substartArray;
    }
    
    public function getUserAppointmentsFromDatabase(Request $request)
    {        
        if ($request->get('userId') && $request->get('propertyId') && $request->get('monthId'))
        {
            $boss = auth()->user();
            
            $userId = htmlentities((int)$request->get('userId'), ENT_QUOTES, "UTF-8");

            // >> look for user within boss workers
            $user = User::where([
                'id' => $userId,
                'boss_id' => $boss->id
            ])->first();
            // <<

            // >> if user doeasn't exist, look for boss itself
            if ($user === null)
            {
                $user = User::where([
                    'id' => $boss->id,
                    'isBoss' => 1
                ])->first();
            }
            // <<

            $propertyId = htmlentities((int)$request->get('propertyId'), ENT_QUOTES, "UTF-8");
            $monthId = htmlentities((int)$request->get('monthId'), ENT_QUOTES, "UTF-8");
            
            $property = Property::where('id', $propertyId)->first();
            $month = Month::where('id', $monthId)->with([
                'year.property',
                'days.appointments.graphic.employee'
            ])->first();

            if ($user !== null && 
                $property !== null && $property->boss_id == $boss->id && 
                $month !== null && $month->year->property->id == $property->id)
            {
                $appointmentsArray = [];

                if (count($month->days) > 0)
                {
                    foreach ($month->days as $day)
                    {
                        if (count($day->appointments) > 0)
                        {
                            foreach ($day->appointments as $appointment)
                            {
                                if ($appointment->user->id == $user->id)
                                {
                                    $appointment->load([
                                        'user',
                                        'item',
                                        'day.month.year'
                                    ]);

                                    // >>
                                    $monthNumber = (string)$appointment->day->month->month_number;

                                    if (strlen($monthNumber) == 1)
                                    {
                                        $monthNumber = "0" . $appointment->day->month->month_number;
                                    }

                                    $employee = $appointment->graphic->employee;

                                    $dateTime = new \DateTime($appointment->day->month->year->year . '-' . $monthNumber . '-' . $appointment->day->day_number . ' ' . $appointment->start_time);

                                    $appointmentsArray[] = [
                                        'date' => $appointment->day->day_number. ' ' . $month->month . ' ' . $month->year->year,
                                        'date_time' => $dateTime->getTimestamp(),
                                        'time' => $appointment->start_time . " - " . $appointment->end_time,
                                        'worker_id' => $appointment->user->id,
                                        'worker' => $appointment->user->name . " " . $appointment->user->surname,
                                        'item' => $appointment->item->name,
                                        'employee_name' => $employee->name . " " . $employee->surname,
                                        'employee_slug' => $employee->slug,
                                        'status' => config('appointment-status.' . $appointment->status),
                                        'day' => $appointment->day->day_number,
                                        'month' => $month->month_number,
                                        'year' => $month->year->year
                                    ];
                                }
                            }
                        }
                    }
                }

                // >> sorting and storing appointments into new array (there was a problem with indexes (after sorting, they remain the same numbers))
                if (count($appointmentsArray) > 0)
                {                
                    uasort($appointmentsArray, function($a, $b) {
                        return $b['date_time'] <=> $a['date_time'];
                    });
                }
                
                $sortedAppointmentsArray = [];

                if (count($appointmentsArray) > 0)
                {                
                    uasort($appointmentsArray, function($a, $b) {
                        return $b['date_time'] <=> $a['date_time'];
                    });

                    foreach ($appointmentsArray as $arr)
                    {
                        $sortedAppointmentsArray[] = $arr;
                    }
                }
                // <<
                              
                $data = [
                    'type' => 'success',
                    'worker_name' => $user->name,
                    'worker_surname' => $user->surname,
                    'appointments' => $sortedAppointmentsArray,
                    'propertyId' => $property->id,
                    'date_description' => \Lang::get('common.date'),
                    'hour_description' => \Lang::get('common.hour'),
                    'name_and_surname_description' => \Lang::get('common.name_and_surname'),
                    'massage_description' => \Lang::get('common.massage'),
                    'executor_description' => \Lang::get('common.executor'),
                    'status_description' => \Lang::get('common.status'),
                ];

                return new JsonResponse($data, 200, array(), true);
            }
        }
        
        return new JsonResponse(array(
            'type'    => 'error',
            'message' => 'Pusty request'            
        ));
    }
    
    public function getUsersAppointmentsFromDatabase(Request $request)
    {        
        if ($request->get('propertyId') && $request->get('monthId'))
        {
            $boss = auth()->user();
            
            $propertyId = htmlentities((int)$request->get('propertyId'), ENT_QUOTES, "UTF-8");
            $monthId = htmlentities((int)$request->get('monthId'), ENT_QUOTES, "UTF-8");

            $property = Property::where([
                'id' => $propertyId,
                'boss_id' => $boss->id
            ])->first();
            $month = Month::where('id', $monthId)->with([
                'year.property',
                'days.appointments.graphic.employee'
            ])->first();

            if ($property !== null && 
                $month !== null && $month->year->property->id == $property->id)
            {
                $appointmentsArray = [];

                if (count($month->days) > 0)
                {
                    foreach ($month->days as $day)
                    {
                        if (count($day->appointments) > 0)
                        {
                            foreach ($day->appointments as $appointment)
                            {
                                $appointment->load([
                                    'user',
                                    'item',
                                    'day.month.year'
                                ]);

                                // >>
                                $monthNumber = (string)$appointment->day->month->month_number;

                                if (strlen($monthNumber) == 1)
                                {
                                    $monthNumber = "0" . $appointment->day->month->month_number;
                                }

                                $employee = $appointment->graphic->employee;

                                $dateTime = new \DateTime($appointment->day->month->year->year . '-' . $monthNumber . '-' . $appointment->day->day_number . ' ' . $appointment->start_time);

                                $appointmentsArray[] = [
                                    'date' => $appointment->day->day_number. ' ' . $month->month . ' ' . $month->year->year,
                                    'date_time' => $dateTime->getTimestamp(),
                                    'time' => $appointment->start_time . " - " . $appointment->end_time,
                                    'worker_id' => $appointment->user->id,
                                    'worker' => $appointment->user->name . " " . $appointment->user->surname,
                                    'item' => $appointment->item->name,
                                    'employee_name' => $employee->name . " " . $employee->surname,
                                    'employee_slug' => $employee->slug,
                                    'status' => config('appointment-status.' . $appointment->status),
                                    'day' => $appointment->day->day_number,
                                    'month' => $month->month_number,
                                    'year' => $month->year->year
                                ];
                            }
                        }
                    }
                }

                // >> sorting and storing appointments into new array (there was a problem with indexes (after sorting, they remain the same numbers))
                $sortedAppointmentsArray = [];

                if (count($appointmentsArray) > 0)
                {                
                    uasort($appointmentsArray, function($a, $b) {
                        return $b['date_time'] <=> $a['date_time'];
                    });

                    foreach ($appointmentsArray as $arr)
                    {
                        $sortedAppointmentsArray[] = $arr;
                    }
                }
                // <<

                $data = [
                    'type' => 'success',
                    'appointments' => $sortedAppointmentsArray,
                    'propertyId' => $property->id,
                    'date_description' => \Lang::get('common.date'),
                    'hour_description' => \Lang::get('common.hour'),
                    'name_and_surname_description' => \Lang::get('common.name_and_surname'),
                    'massage_description' => \Lang::get('common.massage'),
                    'executor_description' => \Lang::get('common.executor'),
                    'status_description' => \Lang::get('common.status'),
                ];       

                return new JsonResponse($data, 200, array(), true);
            }
        }
        
        return new JsonResponse(array(
            'type'    => 'error',
            'message' => 'Pusty request'         
        ));
    }
    
    public function markMessageAsDisplayed(Request $request)
    {        
        if ($request->get('messageId'))
        {
            $messageId = htmlentities((int)$request->get('messageId'), ENT_QUOTES, "UTF-8");
            $message = Message::where('id', $messageId)->first();
            
            if ($message !== null)
            {
                $message->status = 1;
                $message->save();

                return new JsonResponse([
                    'type' => 'success'
                ], 200, array(), true);
            }
        }
        
        return new JsonResponse(array(
            'type'    => 'error'        
        ));
    }
    
    public function getMonthlyPaymentsForDoneAppointments(Request $request)
    {        
        if ($request->get('monthId'))
        {
            $month = Month::where('id', $request->get('monthId'))->with('year')->first();
            
            if ($month !== null)
            {
                // >> get month start and end datetimes + payments 
                $monthNumber = (string)$month->month_number;
                
                if (strlen($monthNumber) == 1)
                {
                    $monthNumber = "0" . $month->month_number;
                }

                $monthStartDateTime = new \DateTime($month->year->year . '-' . $monthNumber . '-' . 1);
                $monthEndDateTime = new \DateTime($month->year->year . '-' . $monthNumber . '-' . cal_days_in_month(CAL_GREGORIAN, $month->month_number, $month->year->year));
                
                $payments = $this->countMonthlyPaymentsForDoneAppointments($month->id);
                // <<

                $data = [
                    'type' => 'success',
                    'monthStartDateTime' => $monthStartDateTime->format('Y-m-d'),
                    'monthEndDateTime' => $monthEndDateTime->format('Y-m-d'),
                    'month' => $month->month,
                    'monthEn' => $month->month_en,
                    'payments' => $payments,
                    'locale' => Session::get('locale') == "pl" ? "pl" : "en",
                    'total_amount_for_done_appointments_description' => \Lang::get('common.total_amount_for_done_appointments'),
                    'discount_description' => \Lang::get('common.discount'),
                    'no_payments_description' => \Lang::get('common.no_payments_description')
                ];       

                return new JsonResponse($data, 200, array(), true);
            }
        }
        
        return new JsonResponse(array(
            'type'    => 'error'        
        ));
    }
    
    private function countMonthlyPaymentsForDoneAppointments($monthId)
    {
        $month = Month::where('id', $monthId)->with('days.appointments')->first();
        $usersWithAppointments = [];
        
        if ($month !== null && count($month->days) > 0)
        {
            foreach ($month->days as $day)
            {
                if (count($day->appointments) > 0)
                {
                    foreach ($day->appointments as $appointment)
                    {
                        if ($appointment->status == 1)
                        {
                            $appointment->load([
                                'user',
                                'item'
                            ]);
                            
                            if (count($usersWithAppointments) == 0)
                            {
                                $usersWithAppointments[] = [
                                    'user' => $appointment->user,
                                    'appointments' => collect([$appointment])
                                ];
                                
                            } else {
                                
                                $existsInArray = false;
                                
                                foreach ($usersWithAppointments as $userWithAppointments)
                                {
                                    if ($userWithAppointments['user']->id == $appointment->user->id)
                                    {
                                        $existsInArray = true;
                                        
                                        $userWithAppointments['appointments']->push($appointment);
                                    }
                                }
                                
                                if ($existsInArray == false)
                                {
                                    $usersWithAppointments[] = [
                                        'user' => $appointment->user,
                                        'appointments' => collect([$appointment])
                                    ];
                                }
                            }
                        }
                    }
                }
            }
        }
        
        if (count($usersWithAppointments) > 0)
        {
            // >> get discount that rely from number of users who had massage in given month
            $highestDiscount = null;
            $discountsThatMatchNumberOfUsers = Discount::where('worker_threshold', '<=', count($usersWithAppointments))->get();
            
            if (count($discountsThatMatchNumberOfUsers) > 0)
            {
                $highestDiscount = $discountsThatMatchNumberOfUsers->last();
            }
            // <<
            
            // >> get total amount with discounts from number of appointments per persona per month + discount that rely from number of users who had massage in given month
            $totalAmount = 0;
            $totalAmountWithoutDiscounts = 0;
            
            foreach ($usersWithAppointments as $userWithAppointments)
            {
                if (count($userWithAppointments['appointments']) > 0)
                {
                    // >> add it all together
                    $userAppointmentsAmount = 0;
                    
                    foreach ($userWithAppointments['appointments'] as $appointment)
                    {
                        $userAppointmentsAmount += $appointment->item->price;
                    }
                    // <<
                    
                    if ($userAppointmentsAmount !== 0)
                    {
                        // if user has more then 4 appointments made in given month, mark it as 4 anyway
                        $userAppointmentsCount = count($userWithAppointments['appointments']) > 4 ? 4 : count($userWithAppointments['appointments']);

                        // >> get both discounts and made discount multiplier out of them
                        $discountFromNumberOfDoneAppointmentsInAGivenMonth = config('appointment-monthly-discount.' . $userAppointmentsCount);
                        $highestDiscountMultiplier = $highestDiscount !== null ? 1 + ($highestDiscount->percent / 100) : 1;
                        $totalDiscount =  $discountFromNumberOfDoneAppointmentsInAGivenMonth * $highestDiscountMultiplier;
                        $totalDiscountMultiplier = 1 - ($totalDiscount / 100);
                        // <<

                        // count and add total amount for appointments made by user in a given month
                        $totalAmount += $userAppointmentsAmount * $totalDiscountMultiplier;
                        $totalAmountWithoutDiscounts += $userAppointmentsAmount;
                    }
                }
            }
            
            if ($totalAmount !== 0)
            {
                $totalDiscountPercentage = round(100 - ($totalAmount * 100) / $totalAmountWithoutDiscounts, 2);

                return [
                    'totalAmountWithoutDiscounts' => $totalAmountWithoutDiscounts,
                    'totalAmount' => $totalAmount,
                    'totalDiscountPercentage' => $totalDiscountPercentage
                ];
            }
        }
        
        return [];
    }
}
