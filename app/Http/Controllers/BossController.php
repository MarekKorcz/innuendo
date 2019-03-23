<?php

namespace App\Http\Controllers;

use App\Code;
use App\Property;
use App\ChosenProperty;
use Illuminate\Http\Request;

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
        $codes = Code::where('boss_id', $boss->id)->with('chosenProperties')->get();
        
        $codesArray = [];
        
        for ($i = 0; $i < count($codes); $i++)
        {
            $properties = [];
            
            foreach ($codes[$i]->chosenProperties as $property)
            {
                $chosenProperty = ChosenProperty::where('property_id', $property->property_id)->where('code_id', $codes[$i]->id)->with('subscriptions')->first();
                $property = Property::where('id', $property->property_id)->with('subscriptions')->first();
                
                $chosenPropertySubscriptions = $chosenProperty->subscriptions;
                $allPropertySubscriptions = $property->subscriptions;
                
                $subscriptions = [];
                
                foreach ($allPropertySubscriptions as $propertySubscription)
                {
                    $isChosen = false;
                    
                    foreach ($chosenPropertySubscriptions as $chosenPropertySubscription)
                    {
                        if ($propertySubscription->id == $chosenPropertySubscription->id)
                        {
                            $isChosen = true;
                            break;
                        }
                    }
                    
                    $subscriptions[] = [
                        'subscription_id' => $propertySubscription->id,
                        'subscription_name' => $propertySubscription->name,
                        'isChosen' => $isChosen
                    ];
                }
                
                $properties[] = [
                    'property_id' => $property->id,
                    'property_name' => $property->name,
                    'subscriptions' => $subscriptions
                ];
            }
            
            $codesArray[$i + 1] = [
                'code_id' => $codes[$i]->id,
                'code' => $codes[$i]->code,
                'properties' => $properties
            ];
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
                
//                $workersArray = [];
//                
//                if ($workers !== null)
//                {
//                    for ($i = 0; $i < count($workers); $i++)
//                    {
//                        $workersArray[$i + 1] = $workers[$i];
//                    }
//                }
                        
                return view('boss.property_show')->with([
                    'property' => $property,
                    'workers' => $workers,
                    'propertyCreatedAt' => $propertyCreatedAt
                ]);
            }
        }
        
        return redirect()->route('welcome');
    }
}
