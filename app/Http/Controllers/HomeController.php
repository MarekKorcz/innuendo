<?php

namespace App\Http\Controllers;


// to tests
use App\Appointment;
use App\PromoCode;
use App\Interval;
use App\InvoiceData;
use App\GraphicRequest;
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
use App\Mail\BossCreateWithPromoCode;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except([
            'welcome',
            'subscriptions',
            'discounts'
        ]);
    }
    
    /**
     * Show the application main page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function welcome()
    {
        $canShowProperties = Property::where('canShow', 1)->get();
        
        return view('welcome')->with([
            'canShowProperties' => $canShowProperties
        ]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
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
    
    public function subscriptions()
    {
        return view('subscriptions');
    }
    
    public function discounts()
    {
        return view('discounts');
    }
    
//    public function test()
//    {   
//    }
}
