<?php

namespace App\Http\Controllers;

use App\ChosenProperty;


//// to tests
//use App\Property;
//use App\Subscription;
//use App\User;
//use Illuminate\Support\Collection;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = auth()->user();
        
        $hasPurchasedSubscription = false;
        $userChosenProperties = ChosenProperty::where('user_id', $user->id)->with('purchases')->get();
        
        if (count($userChosenProperties) > 0)
        {
            foreach ($userChosenProperties as $userChosenProperty)
            {
                foreach ($userChosenProperty->purchases as $purchase)
                {
                    if ($purchase->subscription_id !== null)
                    {
                        $hasPurchasedSubscription = true;
                        break;
                    }
                }
            }
        }
        
        return view('home')->with([
            'user' => $user,
            'hasPurchasedSubscription' => $hasPurchasedSubscription
        ]);
    }
    
//    public function test()
//    {        
////        if ($request->request->all())
////        {
////            $propertyId = htmlentities((int)$request->get('propertyId'), ENT_QUOTES, "UTF-8");
//            $propertyId = 2;
//            $property = Property::where('id', $propertyId)->first();
//            
////            $subscriptionId = htmlentities((int)$request->get('subscriptionId'), ENT_QUOTES, "UTF-8");
//            $subscriptionId = 5;
//            $subscription = Subscription::where('id', $subscriptionId)->first();
//            
//            if ($property !== null && $subscription !== null)
//            {
//                $workers = User::where('boss_id', auth()->user()->id)->with('chosenProperties')->get();
//                $workersCollection = new Collection();
//                
//                foreach ($workers as $worker)
//                {
//                    if (count($worker->chosenProperties) > 0)
//                    {
//                        foreach ($worker->chosenProperties as $chosenProperty)
//                        {
//                            if ($chosenProperty->property_id == $property->id)
//                            {
//                                $chosenProperty = ChosenProperty::where('id', $chosenProperty->id)->with('purchases')->first();
//
//                                if (count($chosenProperty->purchases) > 0)
//                                {
//                                    foreach($chosenProperty->purchases as $purchase)
//                                    {
//                                        if ($purchase->subscription_id == $subscription->id)
//                                        {
//                                            $workersCollection->push($worker);
//                                        }
//                                    }
//                                }
//                            }
//                        }
//                    }
//                }
//                
//                $workersArr = [];
//                
//                foreach ($workersCollection as $workerCollection)
//                {
//                    $workersArr[] = [
//                        'id' => $workerCollection->id,
//                        'name' => $workerCollection->name,
//                        'surname' => $workerCollection->surname,
//                        'email' => $workerCollection->email,
//                        'phone_number' => $workerCollection->phone_number,
//                    ];
//                }
//                
//                dump($workersArr);die;
//
//                $data = [
//                    'type'    => 'succes',
//                    'message' => "Udało się pobrać użytkowników posiadający daną subskrypcje",
//                    'workers' => $workersArr
//                ];
//
//                return new JsonResponse($data, 200, array(), true);
////            }
//        }
//        
////        return new JsonResponse(array(
////            'type'    => "error",
////            'message' => "Pusty request"            
////        ));
//    }
}
