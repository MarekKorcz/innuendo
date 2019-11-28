<?php

namespace App\Http\Controllers;


// to tests
//use App\Appointment;
//use App\Promo;
//use App\PromoCode;
//use App\Category;
//use App\Interval;
//use App\InvoiceData;
//use App\GraphicRequest;
//use App\Substart;
//use App\Purchase;
//use App\ChosenProperty;
//use App\Subscription;
//use App\Item;
//use App\Graphic;
//use App\Calendar;
//use App\Day;
//use App\Month;
//use App\Year;
//use App\TempUser;
//use App\Mail\BossCreateWithPromoCode;
//use Illuminate\Support\Collection;
//use Illuminate\Support\Facades\Hash;
//use Illuminate\Support\Facades\File;
//use Session;

use App\Message;
use App\Property;
use App\User;
use App\Discount;
use App\PolicyConfirmation;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\JsonResponse;
use Redirect;

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
            'discounts',
            'cookiesPolicy',
            'privatePolicy',
            'rodo',
            'contactPageShow',
            'contactPageUpdate',
            'acceptTerms'
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
        
        $showBanner = $this->showPolicyBanner();
        
        return view('welcome')->with([
            'canShowProperties' => $canShowProperties,
            'showBanner' => $showBanner
        ]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $showBanner = $this->showPolicyBanner();
        
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
                'showPurchaseSubscriptionsView' => $showPurchaseSubscriptions,
                'showBanner' => $showBanner
            ]);
        }
        
        return view($route)->with([
            'user' => $user,
            'showBanner' => $showBanner
        ]);
    }
    
    public function subscriptions()
    {
        $showBanner = $this->showPolicyBanner();
        
        return view('subscriptions')->with([
            'showBanner' => $showBanner
        ]);
    }
    
    public function discounts()
    {        
        $showBanner = $this->showPolicyBanner();
        $discounts = Discount::where('id', '!=', null)->get();
        
        if (count($discounts) == 4)
        {
            return view('discounts')->with([
                'discounts' => $discounts,
                'showBanner' => $showBanner
            ]);
        }
        
        return view('welcome')->with([
            'error' => \Lang::get('common.discount_error_description'),
            'showBanner' => $showBanner
        ]);
    }
    
    public function cookiesPolicy()
    {
        $showBanner = $this->showPolicyBanner();
        
        return view('cookies_policy')->with([
            'showBanner' => $showBanner
        ]);
    }
    
    public function privatePolicy()
    {
        $showBanner = $this->showPolicyBanner();
        
        return view('private_policy')->with([
            'showBanner' => $showBanner
        ]);
    }
    
    public function rodo()
    {
        $showBanner = $this->showPolicyBanner();
        
        return view('rodo')->with([
            'showBanner' => $showBanner
        ]);
    } 
    
    public function contactPageShow()
    {
        $showBanner = $this->showPolicyBanner();
        
        return view('contact_page')->with([
            'showBanner' => $showBanner
        ]);
    }
    
    public function contactPageUpdate()
    {
        $rules = array(
            'topic'   => 'required|string',
            'email'   => 'required|email',
            'message' => 'required|string'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return Redirect::to('/contact')
                ->withErrors($validator);
        } else {
            
            $message = new Message();
            $message->topic = Input::get('topic');
            $message->email = Input::get('email');
            $message->text  = Input::get('message');     
            $message->save();

            return redirect()->route('welcome')->with('success', 'Wiadomość została wysłana!');
        }
    }
    
    private function showPolicyBanner()
    {
        $showBanner = true;
        $confirmation = PolicyConfirmation::where('ip_address', $_SERVER['REMOTE_ADDR'])->first();
        
        if ($confirmation !== null)
        {
            $showBanner = false;
        }
        
        return $showBanner; 
    }
    
    public function acceptTerms()
    {        
        $clientIP = $_SERVER['REMOTE_ADDR'];
        $policyConfirm = PolicyConfirmation::where('ip_address', $clientIP)->first();
        
        if ($policyConfirm == null)
        {
            $policyConfirm = new PolicyConfirmation();
            $policyConfirm->ip_address = $clientIP;
            $policyConfirm->confirm = true;
            $policyConfirm->save();
        }
        
        return new JsonResponse([
            'type' => 'success'
        ], 200, array(), true);
    }
    
//    {
//        $filesDirPath = storage_path('app/notes');
//        $filesDir = scandir($filesDirPath);
//        
//        $textFile = storage_path('app') . "/text.txt";
//        
//        for ($i = 2; $i < count($filesDir); $i++)
//        {
//            $directory = scandir($filesDirPath . "/" . $filesDir[$i]);
//            
//            // I used this to change files extension
////            $oldFileName = $filesDirPath . "/" . $filesDir[$i] . "/" . $directory[5];
////            $newFileName = $filesDirPath . "/" . $filesDir[$i] . "/new.json";
////            rename($oldFileName, $newFileName);
//            
//            // this to read notes in every file
////            $fileName = $filesDirPath . "/" . $filesDir[$i] . "/new.json";
////            
////            $file = File::get($fileName);
////            $decodedFile = json_decode($file);
////            $memoObjectList = $decodedFile->MemoObjectList;
////            $note = $memoObjectList[0]->DescRaw;
//            
//            // and this to write all notes to one file
//            $current = file_get_contents($textFile);
//            $current .= $note . "\n\n\n";
//            file_put_contents($textFile, $current);
//        }
//    }
    
    
//    public function test()
//    {
//    }
}
