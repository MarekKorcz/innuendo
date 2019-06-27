<?php

namespace App\Http\Controllers;

//use App\User;
//use App\Property;

// to tests
use App\Appointment;
use App\Interval;
use App\InvoiceData;
use App\Substart;
use App\Purchase;
use App\ChosenProperty;
use App\Subscription;
use App\Property;
use App\User;
use App\Item;
use App\Graphic;
use App\Calendar;
use App\Day;
use App\Month;
use App\Year;
use App\TempUser;
use Illuminate\Support\Collection;

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
        // todo: zanim dokończysz odpowiednie wyświetlanie danych dla różncyh poziomów rejestracji w zależności od posiadanych 
        // praw dostępu czy po prostu danych w db to zadbaj o odpowiednie przypisywanie do szefów propertisów oraz tworzenia dla nich chosenProperties 
        // żeby póżniej na etapie przypisywania codów, workerzy mogli poprawnie przyisać sobie subskrypcje
        
        $user = User::where('id', auth()->user()->id)->with('chosenProperties')->first();
        
        if ($user->isAdmin !== null)
        {
            $route = 'home_admin';
            
        } else if ($user->isEmployee !== null) {
            
            $route = 'home_employee';
            
        } else if ($user->isBoss !== null) {
            
            // todo: zrób żeby kafelek Pakiety nie wyświetlał się kiedy nie ma żadnych chosenProperties
            // dodaj widok do zamawiania (purchase subscrypcji) 
            
//            // >> showPurchaseSubscriptionsView
//            $publicProperties = Property::where('boss_id', null)->with('subscriptions')->get();
//            $showPurchaseSubscriptions = false;
//
//            if (count($publicProperties) > 0)
//            {
//                foreach ($publicProperties as $publicProperty)
//                {
//                    if (count($publicProperty->subscriptions) > 0)
//                    {
//                        $showPurchaseSubscriptions = true;
//                        break;
//                    }
//                }
//            }
//            // >>
//            
//            return view('home')->with([
//                'user' => $user,
//                'showGraphicsView' => $showGraphics,
//                'showSubscriptionsView' => count($user->chosenProperties) > 0 ? true : false,
//                'showPurchaseSubscriptionsView' => $showPurchaseSubscriptions
//            ]);
            
            
            $route = 'home_boss';
            
        } else {
            
            // showGraphicsView
            $showGraphics = false;
            
            if ($user->boss_id)
            {
                $showGraphics = true;
                
            } else {
                
                $properties = Property::where('boss_id', null)->get();
                
                if (count($properties) > 0)
                {
                    $showGraphics = true;
                }
            }
            // >>
            
            // >> showPurchaseSubscriptionsView
            $publicProperties = Property::where('boss_id', null)->with('subscriptions')->get();
            $showPurchaseSubscriptions = false;

            if (count($publicProperties) > 0)
            {
                foreach ($publicProperties as $publicProperty)
                {
                    if (count($publicProperty->subscriptions) > 0)
                    {
                        $showPurchaseSubscriptions = true;
                        break;
                    }
                }
            }
            // >>
            
            return view('home')->with([
                'user' => $user,
                'showGraphicsView' => $showGraphics,
                'showSubscriptionsView' => count($user->chosenProperties) > 0 ? true : false,
                'showPurchaseSubscriptionsView' => $showPurchaseSubscriptions
            ]);
        }
        
        return view($route)->with([
            'user' => $user
        ]);
    }
    
//    public function test()
//    {            
//        
//    }
}
