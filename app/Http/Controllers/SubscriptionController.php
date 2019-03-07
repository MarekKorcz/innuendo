<?php

namespace App\Http\Controllers;

use App\Property;
use App\Subscription;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SubscriptionController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function create($id)
    {
        $property = Property::where('id', $id)->first();
        
        if ($property !== null)
        {
            return view('subscription.create')->with('property_id', $property->id);
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
        $name = $request->get('name');
        $description = $request->get('description');
        $old_price = $request->get('old_price');
        $new_price = $request->get('new_price');
        $quantity = $request->get('quantity');
        $duration = $request->get('duration');
        $property_id = $request->get('property_id');
        
        if ($name !== null && is_string($name) &&
            $description !== null && is_string($description) &&
            $old_price !== null && is_integer((int)$old_price) &&
            $new_price !== null && is_integer((int)$new_price) &&
            $quantity !== null && is_integer((int)$quantity) &&
            $duration !== null && is_integer((int)$duration) &&
            $property_id !== null && is_integer((int)$property_id))
        {            
            $name = htmlentities($name, ENT_QUOTES, "UTF-8");
            $description = htmlentities($description, ENT_QUOTES, "UTF-8");
            $old_price = htmlentities((int)$old_price, ENT_QUOTES, "UTF-8");
            $new_price = htmlentities((int)$new_price, ENT_QUOTES, "UTF-8");
            $quantity = htmlentities((int)$quantity, ENT_QUOTES, "UTF-8");
            $duration = htmlentities((int)$duration, ENT_QUOTES, "UTF-8");
            $property_id = htmlentities((int)$property_id, ENT_QUOTES, "UTF-8");
            
            $subscription = new Subscription();
            $subscription->name = $name;
            $subscription->slug = str_slug($name);
            $subscription->description = $description;
            $subscription->old_price = $old_price;
            $subscription->new_price = $new_price;
            $subscription->quantity = $quantity;
            $subscription->duration = $duration;
            $subscription->save();
            
            $property = Property::where('id', $property_id)->first();
            
            if ($property !== null) 
            {
                $subscription->properties()->attach($property);
                
                return redirect()->action(
                    'SubscriptionController@show', [
                        'slug' => $subscription->slug
                    ]
                )->with('success', 'Subscription has been successfuly added!');
            }
            
            $message = 'Property doesn\'t exist';
        }
        
        return redirect('/subscription/create/' . $property_id)->with('error', $message != null ? $message : 'Incorrect values');
    }
    
    /**
     * Display the specified resource.
     *
     * @param $slug
     * @return Response
     */
    public function show($slug)
    {        
        $subscription = Subscription::where('slug', $slug)->first();
        
        $properties = Property::with('subscriptions')->get();
        
        $propertiesArr = [];
        
        foreach ($properties as $property)
        {
            $active = false;
            
            foreach ($property->subscriptions as $sub)
            {
                if ($sub->id == $subscription->id)
                {
                    $active = true;
                }
            }
            
            $propertiesArr[] = [
                'id' => $property->id,
                'name' => $property->name,
                'active' => $active
            ];
        }
        
        if ($subscription !== null)
        {
            return view('subscription.show')->with('subscription', $subscription)->with('properties', $propertiesArr);
        }
        
        return redirect()->route('welcome')->with('error', 'Subscription does not exist');
    }
    
    /**
     * Shows a list of subscriptions assigned to passed property.
     * 
     * @param int $id
     * @return type
     */
    public function propertySubscriptionIndex($id)
    {
        $property = Property::where('id', $id)->with('subscriptions')->first();
        
        if ($property !== null)
        {            
            $subscriptions = $property->subscriptions;
            
            return view('subscription.index')->with([
                'subscriptions' => $subscriptions
            ]);
        }
        
        return redirect()->route('welcome');
    }
    
    /**
     * Shows a list of all subscriptions.
     * 
     * @return type
     */
    public function subscriptionIndex()
    {
        $subscriptions = Subscription::all();
        
        if ($subscriptions !== null)
        {            
            return view('subscription.index')->with([
                'subscriptions' => $subscriptions
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
    public function destroy($id)
    {
        $subscription = Subscription::find($id);
        
        if ($subscription !== null)
        {
            $subscription->delete();
        
            return redirect('/subscription/index')->with('success', 'Subscription deleted!');
        }
        
        return redirect()->route('welcome');
    }
    
    public function setSubscriptionToProperty(Request $request)
    {        
        if ($request->request->all())
        {
            $subscription = Subscription::where('id', $request->get('subscriptionId'))->first();
            $property = Property::where('id', $request->get('propertyId'))->first();
            
            if ($subscription !== null && $property !== null)
            {
                $active = false;
                
                foreach ($property->subscriptions() as $sub)
                {
                    foreach ($property->subscriptions as $sub)
                    {
                        if ($sub->id == $subscription->id)
                        {
                            $active = true;
                        }
                    }
                }
                
                if ($active)
                {
                    $property->subscriptions()->detach($subscription);
                    
                } else {
                    
                    $property->subscriptions()->attach($subscription);
                }
                
                $data = [
                    'type'    => 'success',
                    'message' => 'Subskrypcja została zmieniona'
                ];
                
                return new JsonResponse($data, 200, array(), true);
                
            } else {
                
                $message = "Lokalizacja lub subskrypcja nie istnieje!";
            }
            
        } else {
            
            $message = "Pusty request";
        }
        
        return new JsonResponse(array(
            'type'    => 'error',
            'message' => $message            
        ));
    }
}