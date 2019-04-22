<?php

namespace App\Http\Controllers;

use App\Code;
use App\Property;
use App\Subscription;
use App\ChosenProperty;
use App\User;
use App\Appointment;
use App\Day;
use App\Month;
use App\Year;
use App\Calendar;
use App\Substart;
use App\Purchase;
use App\Interval;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Redirect;

class BossController extends Controller
{    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('boss');
    }
    
    /**
     * Shows boss codes
     */
    public function codes()
    {
        $boss = auth()->user();
        $codes = Code::where('boss_id', $boss->id)->get();
        $codesArray = [];
        
        if (count($codes) > 0)
        {
            $bossProperties = Property::where('boss_id', $boss->id)->with('chosenProperties')->get();
            
            if (count($bossProperties) > 0)
            {
                for ($i = 0; $i < count($codes); $i++)
                {
                    $properties = [];

                    foreach ($bossProperties as $bossProperty)
                    {                    
                        $subscriptions = [];
                        
                        $allPropertySubscriptions = new Collection();
                        
                        if ($bossProperty->chosenProperties)
                        {
                            foreach ($bossProperty->chosenProperties as $chosenProperty)
                            {
                                $chosenProperty = ChosenProperty::where('id', $chosenProperty->id)->with('purchases')->first();
                                
                                if ($chosenProperty !== null && $chosenProperty->user_id == $boss->id && $chosenProperty->purchases)
                                {
                                    foreach ($chosenProperty->purchases as $purchase)
                                    {
                                        $subscription = Subscription::where('id', $purchase->subscription_id)->first();
                                        $allPropertySubscriptions->push($subscription);
                                    }
                                }
                            }
                        }
                        
                        $chosenProperty = ChosenProperty::where([
                            'property_id' => $bossProperty->id,
                            'code_id' => $codes[$i]->id
                        ])->with('subscriptions')->first();

                        foreach ($allPropertySubscriptions as $propertySubscription)
                        {
                            $isChosen = false;

                            if ($chosenProperty !== null)
                            {
                                $chosenPropertySubscriptions = $chosenProperty->subscriptions;

                                foreach ($chosenPropertySubscriptions as $chosenPropertySubscription)
                                {
                                    if ($propertySubscription->id == $chosenPropertySubscription->id)
                                    {
                                        $isChosen = true;
                                    }
                                }
                            }
                            
                            $isSubscriptionStarted = null;
                            
                            $substart = Substart::where([
                                'property_id' => $bossProperty->id,
                                'subscription_id' => $propertySubscription->id
                            ])->first();

                            if ($substart !== null && $substart->isActive == 1)
                            {
                                $isSubscriptionStarted = "(od " . $substart->start_date->format("Y-m-d") . " do " . $substart->end_date->format("Y-m-d") . ")";
                            }

                            $subscriptions[] = [
                                'subscription_id' => $propertySubscription->id,
                                'subscription_name' => $propertySubscription->name,
                                'isChosen' => $isChosen,
                                'isSubscriptionStarted' => $isSubscriptionStarted
                            ];
                        }

                        $properties[] = [
                            'property_id' => $bossProperty->id,
                            'property_name' => $bossProperty->name,
                            'chosen_property_id' => $chosenProperty !== null ? $chosenProperty->id : 0,
                            'subscriptions' => $subscriptions
                        ];
                    }

                    $codesArray[$i + 1] = [
                        'code_id' => $codes[$i]->id,
                        'code' => $codes[$i]->code,
                        'properties' => $properties
                    ];
                }
            }
        }
        
        return view('boss.codes')->with([
            'codes' => $codesArray
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
            
            // store
            $code = Code::where('id', $codeId)->first();
            
            if ($code !== null)
            {
                $code->code = $codeText;
                $code->save();
                
                // redirect
                return redirect('/boss/codes')->with('success', $message);
            }
        }

        return redirect()->route('welcome');
    }
    
    /**
     * Shows list of properties
     * 
     * @param Request $request
     * @return type
     */
    public function propertyList()
    {
        $boss = auth()->user();
        
        if ($boss !== null)
        {
            $properties = Property::where('boss_id', auth()->user()->id)->get();

            if ($properties !== null)
            {
                if (count($properties) == 1)
                {
                    return redirect()->action(
                        'BossController@property', [
                            'id' => $properties->first()->id
                        ]
                    );
                
                } else {

                    return view('boss.property_list')->with('properties', $properties);
                }
            }
        }
        
        return redirect()->route('welcome');
    }
    
    /**
     * Shows property.
     * 
     * @param integer $id
     * @return type
     */
    public function property($id)
    {
        if ($id !== null)
        {
            $propertyId = htmlentities((int)$id, ENT_QUOTES, "UTF-8");
            $property = Property::where([
                'id' => $propertyId,
                'boss_id' => auth()->user()->id
            ])->first();
            
            if ($property !== null)
            {
                $workers = auth()->user()->getWorkers();                
                $propertyCreatedAt = $property->created_at->format('d.m.Y');
                        
                return view('boss.property_show')->with([
                    'property' => $property,
                    'workers' => $workers,
                    'propertyCreatedAt' => $propertyCreatedAt
                ]);
            }
        }
        
        return redirect()->route('welcome')->with('error', 'Ta lokalizacja nie należy do Ciebie');
    }
    
    /**
     * Shows list of subscriptions
     * 
     * @param type $propertyId
     * @param type $subscriptionId
     * @return type
     */
    public function subscriptionList($propertyId = 0, $subscriptionId = 0)
    {
        $boss = auth()->user();
        
        if ($boss !== null)
        {
            $givenProperty = null;
            $propertyId = htmlentities((int)$propertyId, ENT_QUOTES, "UTF-8");
            $propertyId = (int)$propertyId;
        
            if ($propertyId !== 0)
            {
                $givenProperty = Property::where('id', $propertyId)->where('boss_id', $boss->id)->with('subscriptions')->first();
            }
            
            $chosenSubscription = null;
            $subscriptionId = htmlentities((int)$subscriptionId, ENT_QUOTES, "UTF-8");
            $subscriptionId = (int)$subscriptionId;
            
            if ($subscriptionId !== 0)
            {
                $chosenSubscription = Subscription::where('id', $subscriptionId)->first();
            }            
            
            $properties = Property::where('boss_id', $boss->id)->with('chosenProperties')->get();
            $chosenProperties = new Collection();
            
            foreach ($properties as $property)
            {
                foreach ($property->chosenProperties as $chosenProperty)
                {
                    if ($chosenProperty->user !== null && $chosenProperty->user->id == $boss->id)
                    {
                        $chosenProperty = ChosenProperty::where('id', $chosenProperty->id)->with('purchases')->first();
                        $chosenProperties->push($chosenProperty);
                    }
                }
            }
            
            $propertiesWithPurchasedSubscriptionsArray = [];
            $selectedPropertyItemId = 0;
            
            foreach ($chosenProperties as $key => $chosenProperty)
            {
                $property = Property::where('id', $chosenProperty->property_id)->first();
                                
                if ( ($givenProperty === null && $key == 0) || ($givenProperty !== null && $property->id == $givenProperty->id) )
                {
                    $property['isSelected'] = true;
                    $selectedPropertyItemId = $property->id;

                } else {

                    $property['isSelected'] = false;
                }
                
                $subscriptionsArray = [];
                
                if ($chosenProperty->purchases)
                {
                    foreach ($chosenProperty->purchases as $purchase)
                    {
                        $subscriptionsArray[] = Subscription::where('id', $purchase->subscription_id)->first();
                    }
                }
                
                $propertiesWithPurchasedSubscriptionsArray[] = [
                    'property' => $property,
                    'subscriptions' => $subscriptionsArray
                ];
            }
            
            if (count($propertiesWithPurchasedSubscriptionsArray) > 0)
            {        
                $selectedSubscriptionItemId = 0;
                
                foreach ($propertiesWithPurchasedSubscriptionsArray as $propertyWithPurchasedSubscriptions)
                {
                    if ($propertyWithPurchasedSubscriptions['property']->isSelected == true)
                    {                        
                        foreach ($propertyWithPurchasedSubscriptions['subscriptions'] as $key => $sub)
                        {
                            if ( ($chosenSubscription === null && $key == 0) || ($chosenSubscription !== null && $sub->id == $chosenSubscription->id) )
                            {
                                $sub['isSelected'] = true;
                                $selectedSubscriptionItemId = $sub->id;
                                
                            } else {
                                
                                $sub['isSelected'] = false;
                            }
                        }
                        
                    } else if ($propertyWithPurchasedSubscriptions['property']->isSelected == false) {
                        
                        foreach ($propertyWithPurchasedSubscriptions['subscriptions'] as $sub)
                        {
                            $sub['isSelected'] = false;
                        }
                    }
                }
                
                $workers = $this->getWorkersFrom($selectedPropertyItemId, $selectedSubscriptionItemId);
                                
                return view('boss.subscription_dashboard')->with([
                    'propertiesWithPurchasedSubscriptions' => $propertiesWithPurchasedSubscriptionsArray,
                    'workers' => $workers,
                    'propertyId' => $selectedPropertyItemId,
                    'subscriptionId' => $selectedSubscriptionItemId
                ]);
            }
        }
        
        return redirect()->route('welcome');
    }
    
    /**
     * Subscriptions purchase view (shown differently depending on whether boss owns one or more then one properties)
     * 
     * @return type
     */
    public function propertiesSubscriptionPurchase()
    {
        $boss = auth()->user();
        
        if ($boss !== null)
        {
            $properties = new Collection();
            $ownProperties = Property::where('boss_id', auth()->user()->id)->get();
            $otherProperties = Property::where('boss_id', null)->get();
            
            if ($ownProperties !== null)
            {
                foreach ($ownProperties as $ownProperty)
                {
                    $ownProperty['isOwn'] = true;
                    $properties->push($ownProperty);
                }
            }
            
            if ($otherProperties !== null)
            {
                foreach ($otherProperties as $otherProperty)
                {
                    $otherProperty['isOwn'] = false;
                    $properties->push($otherProperty);
                }
            }

            if ($properties !== null)
            {
                if (count($properties) == 1)
                {
                    return redirect()->action(
                        'BossController@propertySubscriptionsPurchase', [
                            'id' => $properties->first()->id
                        ]
                    );
                
                } else {

                    return view('boss.properties_subscription_purchase')->with([
                        'properties' => $properties
                    ]);
                }
            }
        }
        
        return redirect()->route('welcome');
    }
    
    /**
     * Get subscriptions assigned to passed property.
     * 
     * @param type $id
     */
    public function propertySubscriptionsPurchase($id)
    {
        $boss = auth()->user();
        
        $property = Property::where([
            'id' => $id,
            'boss_id' => $boss->id
        ])->with('subscriptions')->first();
        
        if ($property !== null)
        {
            $subscriptionsCollection = new Collection();
            
            $chosenProperty = ChosenProperty::where([
                'user_id' => $boss->id,
                'property_id' => $property->id
            ])->with('purchases')->first();
            
            foreach ($property->subscriptions as $subscription)
            {
                $isPurchased = false;
                
                if ($chosenProperty !== null && $chosenProperty->purchases)
                {
                    foreach ($chosenProperty->purchases as $purchase)
                    {
                        if ($subscription->id == $purchase->subscription_id)       
                        {
                            $isPurchased = true;
                        }
                    }
                    
                    $subscription['isPurchased'] = $isPurchased;
                    $subscriptionsCollection->push($subscription);
                }
            }
            
            return view('boss.property_subscriptions_purchase')->with([
                'property' => $property,
                'subscriptions' => $subscriptionsCollection
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
        
        if ($propertyId && $subscriptionId)
        {
            $subscription = Subscription::where('id', $subscriptionId)->first();
            $property = Property::where('id', $propertyId)->first();

            if ($subscription !== null && $property !== null)
            {
                $boss = auth()->user();
                $user = User::where('id', $boss->id)->with('chosenProperties')->first();

                if ($user->chosenProperties)
                {
                    foreach ($user->chosenProperties as $chosenProperty)
                    {
                        if ($chosenProperty->property_id == $propertyId)
                        {
                            $chosenProperty = ChosenProperty::where('id', $chosenProperty->id)->with('purchases')->first();

                            if ($chosenProperty->purchases)
                            {
                                foreach ($chosenProperty->purchases as $purchase)
                                {
                                    $userSubscription = Subscription::where('id', $purchase->subscription_id)->first();

                                    if ($userSubscription !== null && $userSubscription->id == $subscription->id)
                                    {
                                        if ($property->boss_id !== null && $property->boss_id == $boss->id)
                                        {
                                            return redirect()->action(
                                                'BossController@subscriptionList', [
                                                    'propertyId' => $property->id,
                                                    'subscriptionId' => $subscription->id
                                                ]
                                            )->with('success', 'Posiadasz już te subskrypcje');
                                            
                                        } else {
                                            
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
                }

                return view('boss.subscription_purchase')->with([
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
        // validate
        $rules = array(
            'terms'             => 'required',
            'property_id'       => 'required',
            'subscription_id'   => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return Redirect::to('boss/subscription/purchase/' . Input::get('property_id') . '/' . Input::get('subscription_id'))
                ->withErrors($validator)
                ->withInput(Input::except('password'));
        } else {

            $subscription = Subscription::where('id', Input::get('subscription_id'))->first();
            $property = Property::where('id', Input::get('property_id'))->first();
            
            if ($subscription !== null && $property !== null)
            {
                // check if such a subscription hasn't already been purchased!
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

                if ($isPurchasable)
                {
                    $chosenProperty = ChosenProperty::where([
                        'property_id' => $property->id,
                        'user_id' => $boss->id
                    ])->first();
                    
                    if ($chosenProperty === null)
                    {
                        $chosenProperty = new ChosenProperty();
                        $chosenProperty->property_id = $property->id;
                        $chosenProperty->user_id = $boss->id;
                        $chosenProperty->subscriptions()->attach($subscription);
                        $chosenProperty->save();
                        
                    } else {
                        
                        $chosenProperty->subscriptions()->attach($subscription);
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
                            'BossController@subscriptionList', [
                                'propertyId' => $property->id,
                                'subscriptionId' => $subscription->id
                            ]
                        )->with('success', 'Subskrypcja dodana. Wiadomość z informacjami została wysłana na maila');
                    }
                }
            }
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
            $message = 'Dodano nowy kod';
            
        } else {
            
            $type = 'error';
            $message = 'Błąd dodawania nowego kodu';
        }
        
        return redirect()->action(
            'BossController@codes'
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
        $code = Code::where('id', $id)->with('chosenProperties')->first();
        
        if ($code !== null)
        {            
            $chosenProperties = $code->chosenProperties;
            
            if ($chosenProperties !== null)
            {
                foreach ($chosenProperties as $chosenProperty)
                {
                    $chosenProperty = ChosenProperty::where('id', $chosenProperty->id)->with('subscriptions')->first();
                    
                    if ($chosenProperty->subscriptions !== null)
                    {
                        $chosenProperty->subscriptions()->detach();
                    }
                    
                    $chosenProperty->delete();
                }
            }
            
            $code->delete();

            $type = 'success';
            $message = 'Kod został usunięty';
            
        } else {
            
            $type = 'error';
            $message = 'Podany kod nie istnieje';
        }
        
        return redirect()->action(
            'BossController@codes'
        )->with($type, $message);
    }
    
    /**
     * Shows a list of appointments assigned to passed property subscription.
     * 
     * @param type $propertyId
     * @param type $subscriptionId
     * @param type $userId
     * 
     * @return type
     */
    public function workerAppointmentList($propertyId, $subscriptionId, $userId = 0)
    {
//        jesli podane id to wczytaj wizyty tego danego usera na ten miesiąc, dotyczące tej danej subskrypcji
//                i wypełnij w widoku placeholder imieniem i nazwiskiem danej osoby
                
//        jesli nie podane id to wczytaj wizyty wszystkich userów na ten miesiąc, dotyczące tej danej subskrypcji
        
//        input wczytujący i wyświetlający wizyty danego usera
        
//        wyświetlanie wizyt w danych miesiącach
        
        
        
        $propertyId = htmlentities((int)$propertyId, ENT_QUOTES, "UTF-8");
        $property = Property::where('id', $propertyId)->first();

        $subscriptionId = htmlentities((int)$subscriptionId, ENT_QUOTES, "UTF-8");
        $subscription = Subscription::where('id', $subscriptionId)->first();
        
        if ($property !== null && $subscription !== null)
        {
            $appointmentsCollection = new Collection();
                    
            if ($userId !== 0 && is_int($userId))
            {
                $workers = User::where('id', $userId)->where('boss_id', auth()->user()->id)->with('chosenProperties')->first();
                
            } else {
                
                $workers = User::where('boss_id', auth()->user()->id)->with('chosenProperties')->get();
            }
            
            if ($workers !== null)
            {
                foreach ($workers as $worker)
                {
                    if ($worker->chosenProperties)
                    {
                        foreach ($worker->chosenProperties as $chosenProperty)
                        {
                            if ($chosenProperty->property_id == $property->id)
                            {
                                $chosenProperty = ChosenProperty::where('id', $chosenProperty->id)->with('purchases')->first();

                                if (count($chosenProperty->purchases) > 0)
                                {
                                    foreach($chosenProperty->purchases as $purchase)
                                    {
                                        if ($purchase->subscription_id == $subscription->id)
                                        {
                                            $appointments = Appointment::where('purchase_id', $purchase->id)->with('item')->orderBy('created_at', 'desc')->get();

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
                                                    
                                                    $employee = $employee->name;
                                                    $appointment['employee'] = $employee;

                                                    $appointmentsCollection->push($appointment);
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            
            return view('boss.worker_appointment_list')->with([
                'appointments' => $appointmentsCollection,
                'worker' => (int)$userId !== 0 ? $worker : null,
                'subscription' => $subscription
            ]);
        }
        
        return redirect()->route('welcome');
    }

    public function setSubscriptionToChosenPropertySubscription(Request $request)
    {        
        if ($request->request->all())
        {
            $chosenPropertyId = htmlentities((int)$request->get('chosenPropertyId'), ENT_QUOTES, "UTF-8");
            $subscriptionId = htmlentities((int)$request->get('subscriptionId'), ENT_QUOTES, "UTF-8");
            
            if ($chosenPropertyId !== null && $subscriptionId !== null)
            {
                $chosenProperty = ChosenProperty::where('id', $chosenPropertyId)->with('subscriptions')->first();
                $subscription = Subscription::where('id', $subscriptionId)->first();

                if ($chosenProperty !== null && $subscription !== null)
                {
                    $active = false;

                    foreach ($chosenProperty->subscriptions as $chosenPropertySubscription)
                    {
                        if ($chosenPropertySubscription->id == $subscription->id)
                        {
                            $active = true;
                        }
                    }

                    if ($active)
                    {
                        $chosenProperty->subscriptions()->detach($subscription);

                    } else {

                        $chosenProperty->subscriptions()->attach($subscription);
                    }

                    $data = [
                        'type'    => 'success',
                        'message' => 'Subskrypcja została zmieniona'
                    ];

                    return new JsonResponse($data, 200, array(), true);

                } else {

                    $message = "Subskrypcja lub zabieg nie istnieje!";
                }
                
            } else {

                $message = "Nieprawidłowe dane requestu";
            }
            
        } else {
            
            $message = "Pusty request";
        }
        
        return new JsonResponse(array(
            'type'    => 'error',
            'message' => $message            
        ));
    }
    
    public function setChosenProperty(Request $request)
    {        
        if ($request->request->all())
        {
            $propertyId = htmlentities((int)$request->get('propertyId'), ENT_QUOTES, "UTF-8");
            $codeId = htmlentities((int)$request->get('codeId'), ENT_QUOTES, "UTF-8");
            $subscriptionId = htmlentities((int)$request->get('subscriptionId'), ENT_QUOTES, "UTF-8");
            
            $message = "Błąd zapytania";
            $type = "error";
            $newChosenPropertyId = 0;
            
            if ($codeId > 0 && $propertyId > 0)
            {
                $chosenProperty = new ChosenProperty();
                $chosenProperty->property_id = $propertyId;
                $chosenProperty->code_id = $codeId;
                $chosenProperty->save();
                
                if ($chosenProperty->id !== null)
                {
                    $subscription = Subscription::where('id', $subscriptionId)->first();
                    
                    if ($subscription !== null)
                    {
                        $chosenProperty->subscriptions()->attach($subscription);
                    }
                    
                    $message = "Lokalizacja została dodana do danego kodu";
                    $type = "success";
                    $newChosenPropertyId = $chosenProperty->id;
                }
            }
            
            $data = [
                'type'    => $type,
                'message' => $message,
                'newChosenPropertyId' => $newChosenPropertyId
            ];

            return new JsonResponse($data, 200, array(), true);
            
        } else {
            
            $message = "Pusty request";
        }
        
        return new JsonResponse(array(
            'type'    => 'error',
            'message' => $message            
        ));
    }
    
    public function deleteChosenProperty(Request $request)
    {        
        if ($request->request->all())
        {
            $chosenPropertyId = htmlentities((int)$request->get('chosenPropertyId'), ENT_QUOTES, "UTF-8");
            
            $message = "Błąd zapytania";
            
            if ($chosenPropertyId > 0) {
                                
                $chosenProperty = ChosenProperty::where('id', $chosenPropertyId)->with('subscriptions')->first();

                if ($chosenProperty !== null)
                {
                    foreach ($chosenProperty->subscriptions as $chosenPropertySubscription)
                    {
                        $chosenProperty->subscriptions()->detach($chosenPropertySubscription);
                    }
                    
                    $chosenProperty->delete();
                    
                    $message = "Lokalizacja została usunięta z danego kodu";
                }
            }
            
            $data = [
                'type'    => 'success',
                'message' => $message
            ];

            return new JsonResponse($data, 200, array(), true);
            
        } else {
            
            $message = "Pusty request";
        }
        
        return new JsonResponse(array(
            'type'    => 'error',
            'message' => $message            
        ));
    }
    
    public function getPropertySubscriptions(Request $request)
    {   
        if ($request->request->all())
        {
            $propertyId = htmlentities((int)$request->get('propertyId'), ENT_QUOTES, "UTF-8");
            $property = Property::where('id', $propertyId)->with('subscriptions')->first();
            
            $message = "Błąd zapytania";
            $type = "error";
            
            if ($property !== null)
            {
                $chosenProperty = ChosenProperty::where('property_id', $property->id)->where('user_id', auth()->user()->id)->with('purchases')->first();
                
                if (count($chosenProperty) > 0)
                {
                    $message = "Subskrypcje danej lokalizacji zostały wczytane";
                    $type = "success";

                    $subscriptions = [];

                    if ($chosenProperty->purchases)
                    {
                        foreach ($chosenProperty->purchases as $purchase)
                        {
                            $subscription = Subscription::where('id', $purchase->subscription_id)->first();
                            
                            if ($subscription !== null)
                            {
                                $subscriptions[] = [
                                    'id' => $subscription->id,
                                    'name' => $subscription->name,
                                    'description' => $subscription->name,
                                    'old_price' => $subscription->old_price,
                                    'new_price' => $subscription->new_price,
                                    'quantity' => $subscription->quantity,
                                    'duration' => $subscription->duration,
                                ];
                            }
                        }
                    }

                    $data = [
                        'type'    => $type,
                        'message' => $message,
                        'propertySubscriptions' => $subscriptions
                    ];

                } else {

                    $message = "Dana lokalizacja nie posiada subskrypcji";
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
    
    public function getSubscriptionWorkers(Request $request)
    {        
        if ($request->request->all())
        {
            $propertyId = htmlentities((int)$request->get('propertyId'), ENT_QUOTES, "UTF-8");
            $subscriptionId = htmlentities((int)$request->get('subscriptionId'), ENT_QUOTES, "UTF-8");
            
            $workers = $this->getWorkersFrom($propertyId, $subscriptionId);
            
            if (count($workers) > 0)
            {
                $data = [
                    'type'    => 'success',
                    'message' => "Udało się pobrać użytkowników posiadający daną subskrypcje",
                    'workers' => $workers
                ];

                return new JsonResponse($data, 200, array(), true);
            }
        }
        
        return new JsonResponse(array(
            'type'    => "error",
            'message' => "Pusty request"            
        ));
    }
    
    private function getWorkersFrom($propertyId, $subscriptionId)
    {
        $property = Property::where('id', $propertyId)->first();
        $subscription = Subscription::where('id', $subscriptionId)->first();

        if ($property !== null && $subscription !== null)
        {
            $workers = User::where('boss_id', auth()->user()->id)->with('chosenProperties')->get();
            $workersCollection = new Collection();

            foreach ($workers as $worker)
            {
                if (count($worker->chosenProperties) > 0)
                {
                    foreach ($worker->chosenProperties as $chosenProperty)
                    {
                        if ($chosenProperty->property_id == $property->id)
                        {
                            $chosenProperty = ChosenProperty::where('id', $chosenProperty->id)->with('purchases')->first();

                            if (count($chosenProperty->purchases) > 0)
                            {
                                foreach($chosenProperty->purchases as $purchase)
                                {
                                    if ($purchase->subscription_id == $subscription->id)
                                    {
                                        $workersCollection->push($worker);
                                    }
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
                ];
            }
            
            return $workersArr;
        }
    }
}
