<?php

namespace App\Http\Controllers;

use App\Property;
use App\Category;
use App\Item;
use App\Subscription;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

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
            $categories = Category::all();
            
            if ($categories !== null)
            {
                $items = new Collection();
                
                foreach ($categories as $category)
                {
                    $categoryItems = Item::where('category_id', $category->id)->get();
                    
                    if ($categoryItems !== null)
                    {
                        $items = $items->merge($categoryItems);
                    }
                }
            
                return view('subscription.create')->with([
                    'property_id' => $property->id,
                    'items' => $items
                ]);
                
            } else {
                
                $message = "Categories do not exist";
            }
            
        } else {
            
            $message = "Property does not exist";
        }
        
        return redirect()->route('welcome')->with('error', $message);
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
        $items = $request->get('items');
        
        if ($name !== null && is_string($name) &&
            $description !== null && is_string($description) &&
            $old_price !== null && is_integer((int)$old_price) &&
            $new_price !== null && is_integer((int)$new_price) &&
            $quantity !== null && is_integer((int)$quantity) &&
            $duration !== null && is_integer((int)$duration) &&
            $property_id !== null && is_integer((int)$property_id) &&
            is_array($items) && count($items))
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
            
            foreach ($items as $item)
            {
                $subscription->items()->attach($item);
            }
            
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
        }
        
        return redirect('/subscription/create/' . $property_id)->with('error', 'Incorrect values');
    }
    
    /**
     * Display the specified resource.
     *
     * @param $slug
     * @return Response
     */
    public function show($slug)
    {        
        $subscription = Subscription::where('slug', $slug)->with('items')->first();
        $properties = Property::with('subscriptions')->get();
        $propertiesArr = [];
        $items = new Collection();
        
        if ($subscription !== null && $properties !== null)
        {
            foreach ($properties as $property)
            {
                $active = false;

                foreach ($property->subscriptions as $sub)
                {
                    if ($sub->id == $subscription->id)
                    {
                        $active = true;
                    }
                    
                    if ($sub !== null)
                    {
                        $categories = Category::all();
                        
                        foreach ($categories as $category)
                        {
                            $categoryItems = Item::where('category_id', $category->id)->get();
                            
                            if ($categoryItems !== null)
                            {
                                $items = $items->merge($categoryItems);
                            }
                        }
                    }
                }

                $propertiesArr[] = [
                    'id' => $property->id,
                    'name' => $property->name,
                    'active' => $active
                ];
            }            
            
            $itemsArr = [];
            
            foreach ($items as $item)
            {
                $active = false;
                
                foreach ($subscription->items as $subscriptionItem)
                {
                    if ($item->id == $subscriptionItem->id)
                    {
                        $active = true;
                    }
                }
                
                $notExistsYet = true;
                
                if (count($itemsArr) > 0)
                {
                    foreach ($itemsArr as $itemElement)
                    {
                        if ($itemElement['id'] == $item->id)
                        {
                            $notExistsYet = false;
                        }
                    }
                }
                
                if ($notExistsYet)
                {
                    $itemsArr[] = [
                        'id' => $item->id,
                        'name' => $item->name . " - " . $item->minutes . " min",
                        'active' => $active
                    ];
                }
            }
            
            return view('subscription.show')->with([
                'subscription' => $subscription,
                'properties' => $propertiesArr,
                'items' => $itemsArr
            ]);
        }
        
        return redirect()->route('welcome')->with('error', 'Something is missing');
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $subscription = Subscription::where('id', $id)->first();
        
        if ($subscription !== null)
        {
            return view('subscription.edit')->with('subscription', $subscription);
        }
        
        return redirect()->route('welcome')->with('error', 'Subscription does not exist');
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request)
    {
        $name = $request->get('name');
        $description = $request->get('description');
        $old_price = $request->get('old_price');
        $new_price = $request->get('new_price');
        $quantity = $request->get('quantity');
        $duration = $request->get('duration');
        $subscription_id = $request->get('subscription_id');
        
        if ($name !== null && is_string($name) &&
            $description !== null && is_string($description) &&
            $old_price !== null && is_integer((int)$old_price) &&
            $new_price !== null && is_integer((int)$new_price) &&
            $quantity !== null && is_integer((int)$quantity) &&
            $duration !== null && is_integer((int)$duration) &&
            $subscription_id !== null && is_integer((int)$subscription_id))
        {            
            $name = htmlentities($name, ENT_QUOTES, "UTF-8");
            $description = htmlentities($description, ENT_QUOTES, "UTF-8");
            $old_price = htmlentities((int)$old_price, ENT_QUOTES, "UTF-8");
            $new_price = htmlentities((int)$new_price, ENT_QUOTES, "UTF-8");
            $quantity = htmlentities((int)$quantity, ENT_QUOTES, "UTF-8");
            $duration = htmlentities((int)$duration, ENT_QUOTES, "UTF-8");
            $subscription_id = htmlentities((int)$subscription_id, ENT_QUOTES, "UTF-8");
            
            // store
            $subscription = Subscription::where('id', $subscription_id)->first();
            $subscription->name        = $name;
            $subscription->description = $description;
            $subscription->old_price   = $old_price;
            $subscription->new_price   = $new_price;
            $subscription->quantity    = $quantity;
            $subscription->duration    = $duration;
            $subscription->save();

            return redirect('/subscription/show/' . $subscription->slug)->with('success', 'Subscription successfully updated!');
        }
        
        return redirect()->route('welcome');
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
                
                foreach ($property->subscriptions as $sub)
                {
                    if ($sub->id == $subscription->id)
                    {
                        $active = true;
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
    
    public function setItemToSubscription(Request $request)
    {        
        if ($request->request->all())
        {
            $subscriptionId = htmlentities((int)$request->get('subscriptionId'), ENT_QUOTES, "UTF-8");
            $itemId = htmlentities((int)$request->get('itemId'), ENT_QUOTES, "UTF-8");
            
            $subscription = Subscription::where('id', $subscriptionId)->first();
            $item = Item::where('id', $itemId)->first();
            
            if ($subscription !== null && $item !== null)
            {
                $active = false;
                
                foreach ($subscription->items as $subscriptionItem)
                {
                    if ($subscriptionItem->id == $item->id)
                    {
                        $active = true;
                    }
                }
                
                if ($active)
                {
                    $subscription->items()->detach($item);
                    
                } else {
                    
                    $subscription->items()->attach($item);
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
            
            $message = "Pusty request";
        }
        
        return new JsonResponse(array(
            'type'    => 'error',
            'message' => $message            
        ));
    }
}