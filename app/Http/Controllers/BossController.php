<?php

namespace App\Http\Controllers;

use App\Code;
use App\Property;
use App\InvoiceData;
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
                                    $today = new \DateTime(date('Y-m-d'));
                                    
                                    foreach ($chosenProperty->purchases as $purchase)
                                    {
                                        $substart = Substart::where('id', $purchase->substart_id)->first();
                                        
                                        if ($substart !== null && $substart->start_date <= $today && $substart->end_date >= $today)
                                        {
                                            $subscription = Subscription::where('id', $purchase->subscription_id)->first();
                                            $allPropertySubscriptions->push($subscription);
                                        }
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
                            
                            $substarts = Substart::where([
                                'property_id' => $bossProperty->id,
                                'subscription_id' => $propertySubscription->id
                            ])->get();

                            if (count($substarts) > 0)
                            {
                                $today = new \DateTime(date('Y-m-d'));
                                
                                foreach ($substarts as $substart)
                                {
                                    if ($substart->start_date <= $today && $substart->end_date >= $today)
                                    {
                                        if ($substart->isActive == 1)
                                        {
                                            $isSubscriptionStarted = "(od " . $substart->start_date->format("Y-m-d") . " do " . $substart->end_date->format("Y-m-d") . ")";
                                            
                                        } else {
                                            
                                            $isSubscriptionStarted = "(jeszcze nie aktywowana)";
                                        }

                                        $subscriptions[] = [
                                            'subscription_id' => $propertySubscription->id,
                                            'subscription_name' => $propertySubscription->name,
                                            'isChosen' => $isChosen,
                                            'isSubscriptionStarted' => $isSubscriptionStarted
                                        ];
                                    }
                                }
                            }
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
            
            $code = Code::where('id', $codeId)->first();
            
            if ($code !== null)
            {
                $code->code = $codeText;
                $code->save();
                
                return redirect('/boss/codes')->with('success', $message);
            }
        }

        return redirect()->route('welcome');
    }
    
//    /**
//     * Shows list of properties
//     * 
//     * @param Request $request
//     * @return type
//     */
//    public function propertyList()
//    {
//        $boss = auth()->user();
//        
//        if ($boss !== null)
//        {
//            $properties = Property::where('boss_id', auth()->user()->id)->get();
//
//            if ($properties !== null)
//            {
//                if (count($properties) == 1)
//                {
//                    return redirect()->action(
//                        'BossController@property', [
//                            'id' => $properties->first()->id
//                        ]
//                    );
//                
//                } else {
//
//                    return view('boss.property_list')->with('properties', $properties);
//                }
//            }
//        }
//        
//        return redirect()->route('welcome');
//    }
    
//    /**
//     * Shows property.
//     * 
//     * @param integer $id
//     * @return type
//     */
//    public function property($id)
//    {        
//        if ($id !== null)
//        {
//            $propertyId = htmlentities((int)$id, ENT_QUOTES, "UTF-8");
//            $property = Property::where([
//                'id' => $propertyId,
//                'boss_id' => auth()->user()->id
//            ])->first();
//            
//            if ($property !== null)
//            {
//                $workers = auth()->user()->getWorkers();                
//                $propertyCreatedAt = $property->created_at->format('d.m.Y');
//                
//                return view('boss.property_show')->with([
//                    'property' => $property,
//                    'workers' => $workers,
//                    'propertyCreatedAt' => $propertyCreatedAt
//                ]);
//            }
//        }
//        
//        return redirect()->route('welcome')->with('error', 'Ta lokalizacja nie należy do Ciebie');
//    }
    
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
            
            $validatedProperty = Property::where('email', Input::get('email'))->first();
            
            if ($validatedProperty !== null && $validatedProperty->boss_id !== $boss->id)
            {
                return redirect('/boss/property/' . Input::get('property_id') . '/edit')->with('error', 'Istnieje już lokalizacja z takim adresem email');
            }
            
            $property = Property::where('id', Input::get('property_id'))->first();
            $property->name          = Input::get('name');
            $property->slug          = str_slug(Input::get('name'));
            $property->street        = Input::get('street');
            $property->street_number = Input::get('street_number');
            $property->house_number  = Input::get('house_number');
            $property->city          = Input::get('city');
            $property->boss_id       = $boss->id;
            $property->save();

            return redirect('boss/property/' . $property->id)->with('success', 'Lokalizacja została zaktualizowana');
        }
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
        $givenProperty = null;
        
        $propertyId = htmlentities((int)$propertyId, ENT_QUOTES, "UTF-8");
        $propertyId = (int)$propertyId;

        if ($propertyId !== 0)
        {
            $givenProperty = Property::where([
                'id' => $propertyId,
                'boss_id' => $boss->id
            ])->with('subscriptions')->first();
        }        

        $chosenSubscription = null;
        
        $subscriptionId = htmlentities((int)$subscriptionId, ENT_QUOTES, "UTF-8");
        $subscriptionId = (int)$subscriptionId;

        if ($subscriptionId !== 0)
        {
            if ($givenProperty !== null && count($givenProperty->subscriptions) > 0)
            {
                foreach ($givenProperty->subscriptions as $subscription)
                {
                    if ($subscription->id === $subscriptionId)
                    {
                        $chosenSubscription = $subscription;
                    }
                }
            }
        }        
        
        $propertiesWithSubscriptions = [];
        $properties = Property::where('boss_id', $boss->id)->with([
            'chosenProperties',
            'subscriptions'
        ])->get();
        
        if (count($properties) > 0)
        {
            foreach ($properties as $property)
            {
                // >> check if property is checked
                $property['isChecked'] = false;
                
                if ($givenProperty !== null && $givenProperty->id === $property->id)
                {
                    $property['isChecked'] = true;
                }
                // <<
                
                $chosenProperty = ChosenProperty::where([
                    'user_id' => $boss->id,
                    'property_id' => $property->id
                ])->with('subscriptions')->first();
                
                $subscriptions = new Collection();
                
                if (count($property->subscriptions) > 0)
                {
                    foreach ($property->subscriptions as $subscription)
                    {
                        // >> check if subscription is checked
                        $subscription['isChecked'] = false;
                
                        if ($chosenSubscription !== null && $chosenSubscription->id === $subscription->id)
                        {
                            $subscription['isChecked'] = true;
                        }
                        // <<
                
                        // >> check if subscription is purchased already
                        if ($chosenProperty !== null && count($chosenProperty->subscriptions) > 0)
                        {
                            foreach ($chosenProperty->subscriptions as $chosenSub)
                            {
                                // >> check if subscription is purchased
                                if ($chosenSub->id == $subscription->id)
                                {
                                    $purchases = Purchase::where([
                                        'subscription_id' => $subscription->id,
                                        'chosen_property_id' => $chosenProperty->id
                                    ])->get();
                                    
                                    if (count($purchases) > 0)
                                    {
                                        $today = new \DateTime(date('Y-m-d'));
                                        
                                        foreach ($purchases as $purchase)
                                        {
                                            // >> when purchased, look for substart
                                            $substart = Substart::where([
                                                'id' => $purchase->substart_id,
                                                'boss_id' => $boss->id,
                                                'purchase_id' => $purchase->id
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
                                                
                                                // get workers assigned to boss purchsed subsription
                                                $substart['workers'] = $this->getWorkersFrom($substart->id);  
                                                
                                                // set substart to purchase
                                                $purchase['substart'] = $substart;
                                            }
                                            // <<
                                        }
                                    }
                                    
                                    $subscription['purchases'] = $purchases;
                                }
                                // <<
                            }
                        }
                        // <<
                        
                        $subscriptions->push($subscription);
                    }
                }
                
                $propertiesWithSubscriptions[] = [
                    'property' => $property,
                    'subscriptions' => $subscriptions
                ];
            }
            
            if (count($propertiesWithSubscriptions) > 0)
            {
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
            }            
            
            // >> get substarts attached to chosen property and subscription
            $substarts = Substart::where([
                'property_id' => $propertyId,
                'subscription_id' => $subscriptionId
            ])->get();
            // <<
            
            return view('boss.subscription_dashboard')->with([
                'propertiesWithSubscriptions' => $propertiesWithSubscriptions,
                'substart' => count($substarts) > 0 ? $substarts->last() : null
            ]);
            
        } else {
            
            return redirect()->route('welcome')->with('error', 'Tak lokalizacja nie należy do Ciebie');
        }
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
            $ownProperties = Property::where('boss_id', $boss->id)->get();
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
                    // turned off with propertySubscriptionsPurchase method
//                    return redirect()->action(
//                        'BossController@propertySubscriptionsPurchase', [
//                            'id' => $properties->first()->id
//                        ]
//                    );
                    
                    return redirect()->action(
                        'BossController@subscriptionList', [
                            'propertyId' => $properties->first()->id,
                            'subscriptionId' => 0
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
    
//    /**
//     * Get subscriptions assigned to passed property.
//     * 
//     * @param type $id
//     */
//    public function propertySubscriptionsPurchase($id)
//    {
//        $boss = auth()->user();
//        
//        $property = Property::where([
//            'id' => $id,
//            'boss_id' => $boss->id
//        ])->with('subscriptions')->first();
//        
//        if ($property !== null)
//        {
//            $subscriptionsCollection = new Collection();
//            
//            $chosenProperty = ChosenProperty::where([
//                'user_id' => $boss->id,
//                'property_id' => $property->id
//            ])->with('purchases')->first();
//            
//            foreach ($property->subscriptions as $subscription)
//            {
//                $isPurchased = false;
//                
//                if ($chosenProperty !== null && $chosenProperty->purchases)
//                {
//                    foreach ($chosenProperty->purchases as $purchase)
//                    {
//                        $substart = Substart::where([
//                            'boss_id' => $boss->id,
//                            'property_id' => $property->id,
//                            'subscription_id' => $subscription->id,
//                            'purchase_id' => $purchase->id
//                        ])->first();
//                        
//                        if ($substart !== null)       
//                        {
//                            $isPurchased = true;
//                        }
//                    }
//                    
//                    $subscription['isPurchased'] = $isPurchased;
//                    $subscriptionsCollection->push($subscription);
//                }
//            }
//            
//            return view('boss.property_subscriptions_purchase')->with([
//                'property' => $property,
//                'subscriptions' => $subscriptionsCollection
//            ]);
//            
//        }
//        
//        return redirect()->route('welcome');        
//    }
    
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
        $subscription = Subscription::where('id', (int)$subscriptionId)->first();
            
        if ($property !== null && $subscription !== null)
        {
            $boss = User::where('id', auth()->user()->id)->with('chosenProperties')->first();

            if ($boss !== null && count($boss->chosenProperties) > 0)
            {
                $today = new \DateTime(date('Y-m-d'));
                
                foreach ($boss->chosenProperties as $chosenProperty)
                {
                    if ($chosenProperty->property_id == $property->id)
                    {
                        $chosenProperty = ChosenProperty::where('id', $chosenProperty->id)->with('purchases')->first();

                        if (count($chosenProperty->purchases) > 0)
                        {
                            foreach ($chosenProperty->purchases as $purchase)
                            {
                                $chosenSubscription = Subscription::where('id', $purchase->subscription_id)->first();
                                
                                if ($chosenSubscription !== null && $chosenSubscription->id == $subscription->id)
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

            return view('boss.subscription_purchase')->with([
                'property' => $property,
                'subscription' => $subscription
            ]);
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
                        $endDate = date('Y-m-d', strtotime("+" . ($subscription->duration - 1) . " month", strtotime($startDate)));
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
                        $interval->available_units = $subscription->quantity * $subscription->duration;

                        $interval->start_date = $startDate;
                        $startDate = date('Y-m-d', strtotime("+" . $subscription->duration . " month", strtotime($startDate)));

                        $endDate = date('Y-m-d', strtotime("-1 day", strtotime($startDate)));
                        $interval->end_date = $endDate;

                        $interval->substart_id = $substart->id;
                        $interval->purchase_id = $purchase->id;
                        $interval->save();
                        // <<

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
//                         todo: detach what...? is it working for sure??
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
     * @param type $substartId
     * @param type $userId
     * 
     * @return type
     */
    public function workerAppointmentList($substartId, $userId = 0)
    {
        $substartId = htmlentities((int)$substartId, ENT_QUOTES, "UTF-8");
        $givenSubstart = Substart::where('id', $substartId)->first();
        
        $userId = htmlentities((int)$userId, ENT_QUOTES, "UTF-8");
        $userId = (int)$userId;
        
        if ($givenSubstart !== null)
        {
            $boss = auth()->user();
                    
            if ($userId !== 0 && is_int($userId))
            {              
                $workers = new Collection();
                
                $worker = User::where([
                    'id' => $userId,
                    'boss_id' => $boss->id
                ])->with('chosenProperties')->first();
                
                if ($worker !== null)
                {
                    $workers->push($worker);
                }
                
            } else {
                
                $workers = User::where('boss_id', $boss->id)->with('chosenProperties')->get();
            }   
            
            // if chosen to see all workers, add boss to workers collection
            if ($userId == 0)
            {
                $workers->prepend($boss);
            }
            
//           todo:  ogarnij tutaj wyswietlanie wizyt na podstawie czasu trwania subskrypcji
//            * jesli jest mozliwosc wyswietlenia perioodu zgodnego z czasem terazniejszym to wyswietl, 
//            * jesli jest nieaktywna subskrypcja wyswietl wszystkie
//            * jesli subskrypcja sie zakonczyla wyswietl ostatni mozliwy
            
            $appointmentsCollection = new Collection();
            
            if (count($workers) > 0)
            {
                foreach ($workers as $worker)
                {
                    if (count($worker->chosenProperties) > 0)
                    {
                        foreach ($worker->chosenProperties as $chosenProperty)
                        {
                            if ($chosenProperty->property_id == $givenSubstart->property_id)
                            {
                                $chosenProperty = ChosenProperty::where('id', $chosenProperty->id)->with('purchases')->first();
                                
                                if (count($chosenProperty->purchases) > 0)
                                {
                                    foreach($chosenProperty->purchases as $purchase)
                                    {
                                        if ($purchase->subscription_id == $givenSubstart->subscription_id)
                                        {
                                            // todo: co jeśli dana subskrypcja zostanie już zużyta (czas jej trwania dobiegnie końca)?
                                            // w substart mam end_date więc po tym mogę sprawdzić. Pomyśl jeszcze jak to ogarnąć w innych widokach 
                                            // żeby działało też jeśli subskrypcja się skończy i ktoś nową weżmie
                                            
                                            // todo: sprawdz czemu w substart start_date i end_date różnią się o równe miesiące, 
                                            // a nie o równe miesiące minus jeden dzień
                                            
                                            // todo: sprawdz czy kiedy boss robi purchase to czy dobrze dodaje subskrypcje do chosen_property_subscription
                                            
                                            $substart = Substart::where('id', $purchase->substart_id)->first();
                                                                                        
                                            if ($substart !== null && $substart->id == $givenSubstart->id)
                                            {
                                                
                                                
                                                
                                                
                                                
                                                
                                                // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
                                                // todo: zmień sposób przechowywania wizyt abonamentowych zanim ogarniesz wyświetlanie tutaj...
                                                // 
                                                // kiedy ktoś kupuje subskrypcje to niech w substart doda się ile zabiegów na miesiąc będzie
                                                // 
                                                // 
                                                // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
                                                
                                                
                                                
                                                
                                                
                                                $today = new \DateTime(date('Y-m-d'));
                                                
                                                $currentInterval = Interval::where('purchase_id', $purchase->id)->where('start_date', '<=', $today)->where('end_date', '>=', $today)->first();
                                                                                                         
                                                if ($currentInterval !== null)
                                                {
                                                    $appointments = Appointment::where([
                                                        'purchase_id' => $purchase->id,
                                                        'interval_id' => $currentInterval->id
                                                    ])->with('item')->orderBy('created_at', 'desc')->get();

                                                    if (count($appointments) > 0)
                                                    {
                                                        foreach ($appointments as $appointment)
                                                        {
                                                            $day = Day::where('id', $appointment->day_id)->first();
                                                            $month = Month::where('id', $day->month_id)->first();
                                                            $year = Year::where('id', $month->year_id)->first();
                                                            $calendar = Calendar::where('id', $year->calendar_id)->first();
                                                            $employee = User::where('id', $calendar->employee_id)->first();

                                                            $date = $day->day_number. ' ' . $month->month . ' ' . $year->year;
                                                            $appointment['date'] = $date;

                                                            $appointment['employee'] = $employee->name;
                                                            
                                                            $appointment['user'] = $worker;

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
                }
            }
            
            $subscription = Subscription::where('id', $givenSubstart->subscription_id)->first();
            
            $substart = Substart::where([
                'id' => $givenSubstart->id,
                'boss_id' => $boss->id,
                'property_id' => $givenSubstart->property_id,
                'subscription_id' => $givenSubstart->subscription_id
            ])->first();
            
            $intervals = Interval::where('purchase_id', $substart->purchase_id)->get();
            
            return view('boss.worker_appointment_list')->with([
                'appointments' => $appointmentsCollection,
                'worker' => (int)$userId !== 0 ? $worker : null,
                'subscription' => $subscription,
                'substart' => $substart,
                'intervals' => $intervals,
                'today' => new \DateTime(date('Y-m-d'))
            ]);
        }
        
        return redirect()->route('welcome');
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
//                $today = date('Y-m-d', strtotime("+6 month", strtotime($today->format("Y-m-d"))));
                
                foreach ($substartIntervals as $interval)
                {
                    if ($interval->start_date < $today && $interval->end_date <= $today) {
                        
                        $interval['state'] = 'existing';
                        
                    } elseif ($interval->start_date > $today || $interval->end_date > $today) {
                        
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
                    $adminInvoiceData = InvoiceData::where([
                        'owner_id' => $admin->id,
                        'property_id' => null
                    ])->first();
                    
                    $bossInvoiceData = InvoiceData::where([
                        'owner_id' => $boss->id,
                        'property_id' => $substart->property_id
                    ])->first();
                    
                    $bossProperty = Property::where('id', $substart->property_id)->first();
                    
                    $workersIntervals = Interval::where('interval_id', $interval->id)->get();
                    $workersIntervals->push($boss);
                    $intervalWorkersCount = count($workersIntervals);
                    
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
    public function makeAGraphicRequest(Request $request)
    {
        dump($request->request->all());die;
        
//        if ($request->appointmentTerm && 
//            $request->graphicId && 
//            $request->calendarId && 
//            $request->year && 
//            $request->month && 
//            $request->day)
//        {
//            session([
//                'appointmentTerm' => $request->appointmentTerm,
//                'graphicId' => $request->graphicId,
//                'calendarId' => $request->calendarId,
//                'year' => $request->year,
//                'month' =>  $request->month,
//                'day' => $request->day
//            ]);
//            
//            if (auth()->user() !== null)
//            {
//                return redirect()->action(
//                    'AppointmentController@create'
//                );
//                
//            } else {
//                
//                return redirect()->route('login');
//            }
//        }
//        
//        return redirect()->route('welcome');
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
        
        // todo: find out if it all works!!!!
        if ($request->request->all())
        {
            $boss = auth()->user();
        
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
                if (count($property->subscriptions) > 0)
                {
                    // >> get all property subscriptions
                    $propertySubscriptions = [];

                    foreach ($property->subscriptions as $propertySubscription)
                    {
                        $propertySubscriptions[] = [
                            'id' => $propertySubscription->id,
                            'name' => $propertySubscription->name,
                            'description' => $propertySubscription->name,
                            'old_price' => $propertySubscription->old_price,
                            'new_price' => $propertySubscription->new_price,
                            'quantity' => $propertySubscription->quantity,
                            'duration' => $propertySubscription->duration,
                            'property_id' => $property->id,
                            'isPurchased' => false
                        ];
                    }
                    // <<

                    // >> get purchased property subscriptions
                    $purchasedSubscriptions = [];

                    if (count($property->chosenProperties) > 0)
                    {
                        foreach ($property->chosenProperties as $chosenProperty)
                        {
                            $chosenProperty = ChosenProperty::where([
                                'id' => $chosenProperty->id,
                                'user_id' => $boss->id
                            ])->with('subscriptions')->first();

                            if ($chosenProperty !== null && count($chosenProperty->subscriptions) > 0)
                            {
                                foreach ($chosenProperty->subscriptions as $subscription)
                                {
                                    $purchasedSubscriptions[] = [
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
                    }
                    // <<

                    if (count($propertySubscriptions) > 0)
                    {
                        if (count($purchasedSubscriptions) > 0)
                        {
                            foreach ($propertySubscriptions as $key => $propertySubscription)
                            {                            
                                foreach ($purchasedSubscriptions as $purchasedSubscription)
                                {                                
                                    if ($propertySubscription['id'] == $purchasedSubscription['id'])
                                    {
                                        $propertySubscriptions[$key]['isPurchased'] = true;
                                    }
                                }
                            }
                        }

                        $message = "Subskrypcje danej lokalizacji zostały wczytane";
                        $type = "success";

                        $data = [
                            'type'    => $type,
                            'message' => $message,
                            'propertySubscriptions' => $propertySubscriptions
                        ];
                    }

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
            $substartId = htmlentities((int)$request->get('substartId'), ENT_QUOTES, "UTF-8");
            $substart = Substart::where('id', $substartId)->first();
            
            $workers = $this->getWorkersFrom($substartId);
            
            if (count($workers) > 0)
            {
                $data = [
                    'type'    => 'success',
                    'message' => "Udało się pobrać użytkowników posiadający daną subskrypcje",
                    'workers' => $workers,
                    'substartId' => $substart->id
                ];

                return new JsonResponse($data, 200, array(), true);
            }
        }
        
        return new JsonResponse(array(
            'type'    => "error",
            'message' => "Pusty request"            
        ));
    }
    
    private function getWorkersFrom($substartId)
    {
        $substart = Substart::where('id', $substartId)->first();

        if ($substart !== null)
        {
            $boss = User::where([
                'id' => auth()->user()->id,
                'isBoss' => 1
            ])->with('chosenProperties')->first();;
            
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
                                        $workersCollection->push($worker);
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
                ];
            }
            
            return $workersArr;
        }
    }
    
    public function getSubscriptionUsersFromDatabase(Request $request)
    {        
        if ($request->get('searchField') && $request->get('substartId'))
        {
            $searchField = htmlentities($request->get('searchField'), ENT_QUOTES, "UTF-8");
            $substartId = htmlentities((int)$request->get('substartId'), ENT_QUOTES, "UTF-8");
                    
            $boss = auth()->user();
            $substart = Substart::where('id', $substartId)->first();

            if ($substart !== null && $substart->boss_id == $boss->id)
            {       
                // >> look for users within boss workers
                $users = User::where([
                                   ['name', 'like', $searchField . '%'],
                                   ['boss_id', $boss->id]
                               ])->orWhere([
                                   ['surname', 'like', $searchField . '%'],
                                   ['boss_id', $boss->id]
                               ])->with('chosenProperties')->get();
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
                               ])->with('chosenProperties')->first();
                // <<

                // >> if searched boss entity exist, add it to users collection
                if (count($bossSearchedEntity) > 0)
                {            
                    $users->push($bossSearchedEntity);
                }
                // <<

                $usersWithPassedSubscription = new Collection();

                if (count($users) > 0)
                {
                    foreach ($users as $user)
                    {
                        if (count($user->chosenProperties) > 0)
                        {                        
                            foreach ($user->chosenProperties as $chosenProperty)
                            {
                                if ($chosenProperty->property_id == $substart->property_id)
                                {
                                    $chosenProperty = ChosenProperty::where('id', $chosenProperty->id)->with('purchases')->first();

                                    if (count($chosenProperty->purchases) > 0)
                                    {                                    
                                        foreach ($chosenProperty->purchases as $purchase)
                                        {
                                            if ($purchase->subscription_id == $substart->subscription_id && $purchase->substart_id == $substart->id)
                                            {
                                                $usersWithPassedSubscription->push($user);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                $data = [
                    'type'    => 'success',
                    'users'  => count($usersWithPassedSubscription) > 0 ? $usersWithPassedSubscription : ""
                ];

                return new JsonResponse($data, 200, array(), true);
            }
        }
        
        return new JsonResponse(array(
            'type'    => 'error',
            'message' => 'Pusty request'            
        ));
    }
    
    public function getSubscriptionSubstarts (Request $request)
    {
        if ($request->get('propertyId') && $request->get('subscriptionId'))
        {
            $propertyId = htmlentities($request->get('propertyId'), ENT_QUOTES, "UTF-8");
            $subscriptionId = htmlentities($request->get('subscriptionId'), ENT_QUOTES, "UTF-8");
            
            $boss = auth()->user();
        
            $property = Property::where([
                'id' => $propertyId,
                'boss_id' => $boss->id
            ])->first();

            $subscription = Subscription::where('id', $subscriptionId)->first();

            if ($property !== null && $subscription !== null)
            {
                $substarts = Substart::where([
                    'boss_id' => $boss->id,
                    'property_id' => $property->id,
                    'subscription_id' => $subscription->id
                ])->get();

                if (count($substarts) > 0)
                {
                    $substarts = $substarts->sortBy('end_date');
                    $newestSubstart = $substarts->last();

                    $workers = $this->getWorkersFrom($newestSubstart->id);                

                    $data = [
                        'type'    => 'success',
                        'substarts' => $this->turnSubstartObjectsToArrays($substarts),
                        'newestSubstart' => $this->turnSubstartObjectsToArrays($newestSubstart),
                        'workers' => $workers,
                        'lastSubstartId' => $substarts->last()->id
                    ];

                    return new JsonResponse($data, 200, array(), true);
                }
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
        
        if (!is_a($substarts, 'Illuminate\Database\Eloquent\Collection') && count($substarts) == 1)
        {
            $isActiveMessage = "";
            
            if ($substarts->end_date < $today)
            {
                $isActiveMessage = "Czas trwania dobiegł końca";
            
            } elseif ($substarts->start_date <= $today && $today <= $substarts->end_date) {
                
                if ($substarts->isActive == 1)
                {
                    $isActiveMessage = "Aktywowana";
                
                } elseif ($substarts->isActive == 0) {
                    
                    $isActiveMessage = "Nieaktywowana";
                }
            }
                                        
            $substartArray[] = [
                'id' => $substarts->id,
                'start_date' => $substarts->start_date->format('Y-m-d'),
                'end_date' => $substarts->end_date->format('Y-m-d'),
                'isActiveMessage' => $isActiveMessage
            ];
            
        } else if (is_a($substarts, 'Illuminate\Database\Eloquent\Collection') && count($substarts) > 0) {
            
            foreach ($substarts as $substart)
            {
                $isActiveMessage = "";
            
                if ($substart->end_date < $today)
                {
                    $isActiveMessage = "Czas trwania dobiegł końca";

                } elseif ($substart->start_date <= $today && $today <= $substart->end_date) {

                    if ($substart->isActive == 1)
                    {
                        $isActiveMessage = "Aktywowana";

                    } elseif ($substart->isActive == 0) {

                        $isActiveMessage = "Nieaktywowana";
                    }
                }
            
                $substartArray[] = [
                    'id' => $substart->id,
                    'start_date' => $substart->start_date->format('Y-m-d'),
                    'end_date' => $substart->end_date->format('Y-m-d'),
                    'isActiveMessage' => $isActiveMessage
                ];
            }
        }
        
        return $substartArray;
    }
    
    public function getUserAppointmentsFromDatabase(Request $request)
    {        
        if ($request->get('userId') && $request->get('substartId') && $request->get('intervalId'))
        {
            $boss = auth()->user();
            
            $userId = htmlentities((int)$request->get('userId'), ENT_QUOTES, "UTF-8");
            $substartId = htmlentities((int)$request->get('substartId'), ENT_QUOTES, "UTF-8");
            $intervalId = htmlentities((int)$request->get('intervalId'), ENT_QUOTES, "UTF-8");

            // >> look for user within boss workers
            $user = User::where([
                'id' => $userId,
                'boss_id' => $boss->id
            ])->with('chosenProperties')->first();
            // <<

            // >> if user doeasn't exist, look for boss itself
            if ($user === null)
            {
                $user = User::where([
                    'id' => $boss->id,
                    'isBoss' => 1
                ])->with('chosenProperties')->first();
            }
            // <<

            $substart = Substart::where('id', $substartId)->first();
            $interval = Interval::where('id', $intervalId)->first();

            if ($user !== null && $substart !== null && $substart->boss_id == $boss->id && $interval !== null)
            {
                $appointments = new Collection();

                if ($user->chosenProperties)
                {
                    foreach ($user->chosenProperties as $chosenProperty)
                    {
                        if ($chosenProperty->property_id == $substart->property_id)
                        {
                            $chosenProperty = ChosenProperty::where('id', $chosenProperty->id)->with('purchases')->first();

                            if (count($chosenProperty->purchases) > 0)
                            {
                                foreach ($chosenProperty->purchases as $purchase)
                                {
                                    if ($purchase->subscription_id == $substart->subscription_id && $purchase->substart_id == $substart->id)
                                    {
                                        $userIntervals = Interval::where('purchase_id', $purchase->id)->get();

                                        if (count($userIntervals) > 0)
                                        {
                                            foreach ($userIntervals as $userInterval)
                                            {
                                                if ($userInterval->start_date == $interval->start_date && $userInterval->end_date == $interval->end_date)
                                                {
                                                    $userAppointments = Appointment::where([
                                                        'user_id' => $user->id,
                                                        'interval_id' => $userInterval->id,
                                                        'purchase_id' => $purchase->id
                                                    ])->with('item')->get();

                                                    if (count($userAppointments) > 0)
                                                    {
                                                        foreach ($userAppointments as $userAppointment)
                                                        {
                                                            $userAppointment['worker'] = $user;

                                                            $appointments->push($userAppointment);
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
                }

                $appointmentsArray = [];

                if (count($appointments) > 0)
                {
                    foreach ($appointments as $appointment)
                    {
                        $day = Day::where('id', $appointment->day_id)->first();
                        $month = Month::where('id', $day->month_id)->first();
                        $year = Year::where('id', $month->year_id)->first();
                        $date = $day->day_number. ' ' . $month->month . ' ' . $year->year;

                        $calendar = Calendar::where('id', $year->calendar_id)->first();
                        $employee = User::where('id', $calendar->employee_id)->first();

                        $appointmentStatus = config('appointment-status.' . $appointment->status);

                        $appointmentsArray[] = [
                            'date' => $date,
                            'time' => $appointment->start_time . " - " . $appointment->end_time,
                            'worker' => $appointment->worker->name . " " . $appointment->worker->surname,
                            'item' => $appointment->item->name,
                            'employee' => $employee->name,
                            'status' => $appointmentStatus
                        ];
                    }
                }

                $data = [
                    'type' => 'success',
                    'appointments' => $appointmentsArray
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
        if ($request->get('intervalId') && $request->get('substartId'))
        {
            $intervalId = htmlentities((int)$request->get('intervalId'), ENT_QUOTES, "UTF-8");
            $substartId = htmlentities((int)$request->get('substartId'), ENT_QUOTES, "UTF-8");
            $boss = User::where('id', auth()->user()->id)->with('chosenProperties')->first();

            $bossMainInterval = Interval::where('id', $intervalId)->first();
            $substart = Substart::where('id', $substartId)->first();
            $workers = User::where('boss_id', $boss->id)->with('chosenProperties')->get();

            if ($bossMainInterval !== null && $substart !== null && $substart->boss_id == $boss->id && count($workers) > 0)
            {           
                $appointments = new Collection();

                if (count($boss->chosenProperties) > 0)
                {
                    foreach ($boss->chosenProperties as $chosenProperty)
                    {
                        if ($chosenProperty->property_id == $substart->property_id)
                        {
                            $chosenProperty = ChosenProperty::where('id', $chosenProperty->id)->with('purchases')->first();

                            if (count($chosenProperty->purchases) > 0)
                            {
                                foreach ($chosenProperty->purchases as $purchase)
                                {                                
                                    if ($purchase->subscription_id == $substart->subscription_id && $purchase->substart_id == $substart->id)
                                    {
                                        $bossIntervals = Interval::where('purchase_id', $purchase->id)->get();

                                        if (count($bossIntervals) > 0)
                                        {
                                            foreach ($bossIntervals as $bossInterval)
                                            {
                                                if ($bossInterval->interval_id === null && 
                                                    $bossInterval->substart_id == $substart->id && 
                                                    $bossInterval->start_date == $bossMainInterval->start_date && 
                                                    $bossInterval->end_date == $bossMainInterval->end_date
                                                    )
                                                {
                                                    $bossAppointments = Appointment::where([
                                                        'user_id' => $boss->id,
                                                        'interval_id' => $bossInterval->id,
                                                        'purchase_id' => $purchase->id
                                                    ])->with('item')->get();

                                                    if (count($bossAppointments) > 0)
                                                    {
                                                        foreach ($bossAppointments as $bossAppointment)
                                                        {
                                                            $bossAppointment['worker'] = $boss;

                                                            $appointments->push($bossAppointment);
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
                }

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
                                    foreach ($chosenProperty->purchases as $purchase)
                                    {
                                        if ($purchase->subscription_id == $substart->subscription_id && $purchase->substart_id == $substart->id)
                                        {
                                            $workerIntervals = Interval::where('purchase_id', $purchase->id)->get();

                                            if (count($workerIntervals) > 0)
                                            {
                                                foreach ($workerIntervals as $workerInterval)
                                                {
                                                    if ($workerInterval->interval_id !== null && 
                                                        $workerInterval->interval_id == $bossMainInterval->id && 
                                                        $workerInterval->start_date == $bossMainInterval->start_date && 
                                                        $workerInterval->end_date == $bossMainInterval->end_date
                                                        )
                                                    {
                                                        $workerAppointments = Appointment::where([
                                                            'user_id' => $worker->id,
                                                            'interval_id' => $workerInterval->id,
                                                            'purchase_id' => $purchase->id
                                                        ])->with('item')->get();

                                                        if (count($workerAppointments) > 0)
                                                        {
                                                            foreach ($workerAppointments as $workerAppointment)
                                                            {
                                                                $workerAppointment['worker'] = $worker;

                                                                $appointments->push($workerAppointment);
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
                    }
                }

                $appointmentsArray = [];

                if (count($appointments) > 0)
                {
                    foreach ($appointments as $appointment)
                    {
                        $day = Day::where('id', $appointment->day_id)->first();
                        $month = Month::where('id', $day->month_id)->first();
                        $year = Year::where('id', $month->year_id)->first();
                        $date = $day->day_number. ' ' . $month->month . ' ' . $year->year;

                        $calendar = Calendar::where('id', $year->calendar_id)->first();
                        $employee = User::where('id', $calendar->employee_id)->first();

                        $appointmentStatus = config('appointment-status.' . $appointment->status);

                        $appointmentsArray[] = [
                            'date' => $date,
                            'time' => $appointment->start_time . " - " . $appointment->end_time,
                            'worker' => $appointment->worker->name . " " . $appointment->worker->surname,
                            'item' => $appointment->item->name,
                            'employee' => $employee->name,
                            'status' => $appointmentStatus
                        ];
                    }
                }

                $data = [
                    'type' => 'success',
                    'appointments' => $appointmentsArray
                ];

                return new JsonResponse($data, 200, array(), true);
            }
        }
        
        return new JsonResponse(array(
            'type'    => 'error',
            'message' => 'Pusty request'         
        ));
    }
}
