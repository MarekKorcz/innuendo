<?php

namespace App\Http\Controllers;

use App\Code;
use App\Property;
use App\Subscription;
use App\ChosenProperty;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

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
     * Shows boss dashboard
     */
    public function dashboard()
    {
        $boss = auth()->user();
        $codes = Code::where('boss_id', $boss->id)->get();
        $codesArray = [];
        
        if (count($codes) > 0)
        {
            $bossProperties = Property::where('boss_id', $boss->id)->with('subscriptions')->get();

            for ($i = 0; $i < count($codes); $i++)
            {
                $properties = [];

                foreach ($bossProperties as $bossProperty)
                {                    
                    $subscriptions = [];
                    $allPropertySubscriptions = $bossProperty->subscriptions;
                    $chosenProperty = ChosenProperty::where('property_id', $bossProperty->id)->where('code_id', $codes[$i]->id)->with('subscriptions')->first();
                    
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

                        $subscriptions[] = [
                            'subscription_id' => $propertySubscription->id,
                            'subscription_name' => $propertySubscription->name,
                            'isChosen' => $isChosen
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
        
        return view('boss.dashboard')->with([
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
                return redirect('/boss/dashboard')->with('success', $message);
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
                    
                    $propertiesArray = [];

                    for ($i = 0; $i < count($properties); $i++)
                    {
                        $propertiesArray[$i + 1] = $properties[$i];
                    }

                    return view('boss.property_list')->with('properties', $propertiesArray);
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
            $property = Property::where('id', $id)->first();
            
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
        
        return redirect()->route('welcome');
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
            'BossController@dashboard'
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
            'BossController@dashboard'
        )->with($type, $message);
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
}
