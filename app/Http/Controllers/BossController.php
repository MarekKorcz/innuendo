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
use App\Calendar;
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
     * Shows boss codes
     */
    public function codes()
    {
        $boss = User::where('id', auth()->user()->id)->with('chosenProperties')->first();
        $codes = Code::where('boss_id', $boss->id)->get();
        $codesArray = [];
        $redirectToSubscriptionPurchaseView = true;
        
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
        
        if (count($codesArray) == 0)
        {
            if (count($boss->chosenProperties) > 0)
            {
                $redirectToSubscriptionPurchaseView = false;
            }
        }
        
        return view('boss.codes')->with([
            'codes' => $codesArray,
            'redirectToSubscriptionPurchaseView' => $redirectToSubscriptionPurchaseView
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
                                $purchases = new Collection();
                                
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
                                }
                                // <<
                                
                                if ($subscription['propertyPurchases'] == null || count($subscription['propertyPurchases']) == 0)
                                {
                                    $subscription['propertyPurchases'] = $purchases;
                                }
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
            
            return redirect()->route('welcome')->with('error', 'Ta lokalizacja nie należy do Ciebie');
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
        $subscription = Subscription::where('id', (int)$subscriptionId)->with('items')->first();
            
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
            $message = 'Dodano nowy kod. Wybierz lokalizacje oraz subskrypcje które chcesz udostępnić swoim pracownikom, następnie włącz rejestracje oraz wyślij im wygenerowany kod z którym będą mogli się zarejestrować!';
            
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
            $boss = User::where('id', auth()->user()->id)->with('chosenProperties')->first();
                    
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
                                            
                                            // todo: sprawdz czy kiedy boss robi purchase to czy dobrze dodaje subskrypcje do chosen_property_subscription
                                            
                                            $substart = Substart::where('id', $purchase->substart_id)->first();
                                                                                        
                                            if ($substart !== null && $substart->id == $givenSubstart->id)
                                            {                                                
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

                                                            $appointment['employee'] = $employee->name . " " . $employee->surname;
                                                            $appointment['employee_slug'] = $employee->slug;
                                                            
                                                            $appointment['user'] = $worker;
                                                            
                                                            $appointment['interval_id'] = $currentInterval->id;

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
    
    /**
     * Shows boss worker.
     *
     * @param type $workerId
     * @param type $substartId
     * @param type $intervalId
     * 
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function workerShow($workerId, $substartId, $intervalId)
    {        
        $boss = auth()->user();
        
        $worker = User::where([
            'id' => $workerId
        ])->first();
        
        if ($worker !== null)
        {
            if ($worker->isBoss !== null)
            {
                $worker = $boss;
            }
            
            $substart = Substart::where([
                'id' => $substartId
            ])->first();
            
            if ($substart !== null)
            {
                if ($worker->isBoss !== null)
                {
                    $interval = Interval::where([
                        'id' => $intervalId,
                        'substart_id' => $substart->id
                    ])->first();
                    
                } else {
                    
                    $interval = Interval::where([
                        'id' => $intervalId
                    ])->first();
                }
                
                if ($interval !== null)
                {
                    $appointments = Appointment::where([
                        'interval_id' => $interval->id,
                        'user_id' => $worker->id
                    ])->get();

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

                            $appointment['employee'] = $employee->name . " " . $employee->surname;
                            $appointment['employee_slug'] = $employee->slug;
                        }
                    }
                    
                    $substartIntervals = Interval::where([
                        'substart_id' => $substart->id
                    ])->get();
                    
                    $subscription = Subscription::where('id', $substart->subscription_id)->first();
                    
                    return view('boss.worker_show')->with([
                        'worker' => $worker,
                        'substart' => $substart,
                        'interval' => $interval,
                        'substartIntervals' => $substartIntervals,
                        'subscription' => $subscription,
                        'appointments' => $appointments
                    ]);
                }
            }
        }
        
        return redirect()->route('welcome');
    }
    
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
                        if (count(Input::get('workers_on')) > 0)
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
                        
                        if (count(Input::get('workers_off')) > 0)
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
                
                
                
                
                
                
                
                
                
                
                
                
                $today = date('Y-m-d', strtotime("+1 month", strtotime($today->format("Y-m-d"))));
                
                
                
                
                
                
                
                
                
                
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
            'calendar'   => 'required|numeric',
            'year'       => 'required|numeric',
            'month'      => 'required|numeric',
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
                $calendar = Calendar::where('id', Input::get('calendar'))->first();
                
                if ($calendar !== null)
                {
                    $bossProperty = Property::where([
                        'id' => $calendar->property_id,
                        'boss_id' => $boss->id
                    ])->first();
            
                    if ($bossProperty !== null)
                    {
                        $year = Year::where('id', Input::get('year'))->first();
                        
                        if ($year !== null)
                        {
                            $month = Month::where([
                                'id' => Input::get('month'),
                                'year_id' => $year->id
                            ])->first();
                            
                            if ($month !== null)
                            {
                                $day = Day::where([
                                    'day_number' => Input::get('day'),
                                    'month_id' => $month->id
                                ])->first();
                                
                                if ($day !== null)
                                {
                                    $graphicRequest = new GraphicRequest();
                                    $graphicRequest->start_time = Input::get('start_time');
                                    $graphicRequest->end_time = Input::get('end_time');
                                    $graphicRequest->comment = Input::get('comment');
                                    $graphicRequest->property_id = $bossProperty->id;
                                    $graphicRequest->year_id = $year->id;
                                    $graphicRequest->year_number = $year->year;
                                    $graphicRequest->month_id = $month->id;
                                    $graphicRequest->month_number= $month->month_number;
                                    $graphicRequest->day_id = $day->id;
                                    $graphicRequest->day_number = $day->day_number;
                                    $graphicRequest->boss_id = $boss->id;
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
                        }
                    }
                }
            }
            
            
            return redirect()->route('welcome')->with('error', 'Coś poszło nie tak');
        }
    }
    
    public function graphicRequests()
    {
        $boss = User::where('id', auth()->user()->id)->with('chosenProperties')->first();
        
        $graphicRequests = GraphicRequest::where('boss_id', $boss->id)->with([
            'property',
            'year',
            'month',
            'day',
            'employees'
        ])->get();
        
        $property = null;

        if (count($graphicRequests) > 0)
        {
            foreach ($graphicRequests as $graphicRequest)
            {                
                if ($graphicRequest->comment !== null && strlen($graphicRequest->comment) > 24)
                {
                    $graphicRequest->comment = substr($graphicRequest->comment, 0, 24).'...';
                }
            }
        } else {
            
            if (count($boss->chosenProperties) > 0)
            {
                $property = Property::where('id', $boss->chosenProperties->first()->property_id)->first();
            }
        }
        
        return view('boss.graphic_requests')->with([
            'graphicRequests' => $graphicRequests,
            'property' => $property
        ]);
    }
    
    public function graphicRequestShow($graphicRequestId, $chosenMessageId = 0)
    {
        $boss = auth()->user();
        
        $graphicRequest = GraphicRequest::where([
            'id' => $graphicRequestId,
            'boss_id' => $boss->id
        ])->with([
            'property',
            'year',
            'month',
            'day',
            'employees'
        ])->first();
        
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
            
            $chosenMessage = Message::where('id', $chosenMessageId)->first();
            
            if ($chosenMessage !== null && $chosenMessage->owner_id !== $boss->id)
            {
                $chosenMessage->status = 1;
                $chosenMessage->save();
            }
            
            $graphicRequestMessages = Message::where('graphic_request_id', $graphicRequest->id)->get();
            
            return view('boss.graphic_request')->with([
                'graphicRequest' => $graphicRequest,
                'graphicRequestMessages' => $graphicRequestMessages,
                'chosenMessage' => $chosenMessage !== null ? $chosenMessage : null,
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
        $boss = auth()->user();
        $promoCode = PromoCode::where('boss_id', $boss->id)->with([
            'messages',
            'promo',
            'subscriptions'
        ])->first();
        
        if ($promoCode !== null)
        {
            return view('boss.approve_messages')->with([
                'promoCode' => $promoCode,
                'boss' => $boss
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
                    'id' => Input::get('graphic_request_id'),
                    'boss_id' => $boss->id
                ])->first();

                if ($graphicRequest !== null)
                {
                    $message = new Message();
                    $message->text = Input::get('text');
                    $message->status = 0;
                    $message->owner_id = $graphicRequest->boss_id;
                    $message->graphic_request_id = $graphicRequest->id;
                    $message->save();
                    
                    return redirect('/boss/graphic-request/' . $graphicRequest->id . '/' . $message->id)->with('success', 'Wiadomość została wysłana!');
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
                    $message->owner_id = $promoCode->boss_id;
                    $message->promo_code_id = $promoCode->id;
                    $message->save();
                    
                    return redirect('/boss/approve/messages')->with('success', 'Wiadomość została wysłana!');
                }
            }
            
            return redirect()->route('welcome')->with('error', 'Coś poszło nie tak');
        }
    }

    public function setSubscriptionToChosenPropertySubscription(Request $request)
    {        
        if ($request->request->all())
        {
            $chosenPropertyId = htmlentities((int)$request->get('chosenPropertyId'), ENT_QUOTES, "UTF-8");
            $subscriptionId = htmlentities((int)$request->get('subscriptionId'), ENT_QUOTES, "UTF-8");
            
            if ($chosenPropertyId !== 0 && $subscriptionId !== 0)
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
            $subscriptionId = htmlentities((int)$request->get('subscriptionId'), ENT_QUOTES, "UTF-8");
            $codeId = htmlentities((int)$request->get('codeId'), ENT_QUOTES, "UTF-8");
            
            $message = "Błąd zapytania";
            $type = "error";
            $newChosenPropertyId = 0;
            
            if ($codeId > 0 && $propertyId > 0 && $codeId !== 0)
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
                            'name_description' => \Lang::get('common.label'),
                            'description_description' => \Lang::get('common.description'),
                            'description' => $propertySubscription->description,
                            'old_price' => $propertySubscription->old_price . " zł " . \Lang::get('common.per_person'),
                            'old_price_description' => \Lang::get('common.regular_price'),
                            'new_price' => $propertySubscription->new_price . " zł " . \Lang::get('common.per_person'),
                            'new_price_description' => \Lang::get('common.price_with_subscription'),
                            'quantity' => $propertySubscription->quantity,
                            'quantity_description' => \Lang::get('common.number_of_massages_to_use_per_month'),
                            'duration' => $propertySubscription->duration,
                            'duration_description' => \Lang::get('common.subscription_duration'),
                            'button' => route('subscriptionPurchaseView', [
                                'propertyId' => $property->id,
                                'subscriptionId' => $propertySubscription->id
                            ]),
                            'button_description' => \Lang::get('common.purchase_subscription'),
                            'isPurchased' => false,
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
        $substartId = htmlentities((int)$request->get('substartId'), ENT_QUOTES, "UTF-8");
        $substart = Substart::where('id', $substartId)->first();

        $workers = $this->getWorkersFrom($substartId);

        if (count($workers) > 0)
        {
            $data = [
                'type'    => 'success',
                'message' => "Udało się pobrać użytkowników posiadający daną subskrypcje",
                'workers' => $workers,
                'header_workers' => \Lang::get('common.people_assigned_to_subscription'),
                'subscription_workers_edit_button' => route('subscriptionWorkersEdit', [
                    'substartId' => $substart->id,
                    'intervalId' => 0
                ]),
                'subscription_workers_edit_button_description' => \Lang::get('common.edit'),
                'worker_appointment_list_button' => route('workerAppointmentList', [
                    'substartId' => $substart->id,
                    'userId' => 0
                ]),
                'worker_appointment_list_button_description' => \Lang::get('common.all_massages'),
                'show_button_description' => \Lang::get('common.show'),
                'name_description' => \Lang::get('common.name'),
                'surname_description' => \Lang::get('common.surname'),
                'email_description' => \Lang::get('common.email_address'),
                'phone_number_description' => \Lang::get('common.phone_number'),
                'appointments_description' => \Lang::get('common.appointments')
            ];

            return new JsonResponse($data, 200, array(), true);
        }
        
        return new JsonResponse(array(
            'type'    => "error",
            'message' => "Pusty request",
            'no_people_assigned_to_subscription' => \Lang::get('common.no_people_assigned_to_subscription'),
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
                        'header'    => \Lang::get('common.subscription_duration_period'),
                        'substarts' => $this->turnSubstartObjectsToArrays($substarts),
                        'newestSubstart' => $this->turnSubstartObjectsToArrays($newestSubstart),
                        'workers' => $workers,
                        'lastSubstartId' => $substarts->last()->id,
                        'header_workers' => \Lang::get('common.people_assigned_to_subscription'),
                        'subscription_workers_edit_button' => route('subscriptionWorkersEdit', [
                            'substartId' => $substarts->last()->id,
                            'intervalId' => 0
                        ]),
                        'subscription_workers_edit_button_description' => \Lang::get('common.edit'),
                        'worker_appointment_list_button' => route('workerAppointmentList', [
                            'substartId' => $substarts->last()->id,
                            'userId' => 0
                        ]),
                        'worker_appointment_list_button_description' => \Lang::get('common.all_massages'),
                        'show_button_description' => \Lang::get('common.show'),
                        'name_description' => \Lang::get('common.name'),
                        'surname_description' => \Lang::get('common.surname'),
                        'email_description' => \Lang::get('common.email_address'),
                        'phone_number_description' => \Lang::get('common.phone_number'),
                        'appointments_description' => \Lang::get('common.appointments'),
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
        
        if (!is_a($substarts, 'Illuminate\Database\Eloquent\Collection') && count($substarts) == 1)
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
                            'worker_id' => $appointment->worker->id,
                            'worker' => $appointment->worker->name . " " . $appointment->worker->surname,
                            'item' => $appointment->item->name,
                            'employee' => $employee->name . " " . $employee->surname,
                            'employee_slug' => $employee->slug,
                            'status' => $appointmentStatus,
                            'substart_id' => $substart->id,
                            'interval_id' => $intervalId
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
                            'worker_id' => $appointment->worker->id,
                            'worker' => $appointment->worker->name . " " . $appointment->worker->surname,
                            'item' => $appointment->item->name,
                            'employee' => $employee->name . " " . $employee->surname,
                            'employee_slug' => $employee->slug,
                            'status' => $appointmentStatus,
                            'substart_id' => $substart->id,
                            'interval_id' => $intervalId
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
}
