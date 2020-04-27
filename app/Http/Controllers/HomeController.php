<?php

namespace App\Http\Controllers;

// to tests
use App\Appointment;
use App\Category;
use App\InvoiceData;
use App\GraphicRequest;
use App\Item;
use App\Graphic;
use App\Day;
use App\Month;
use App\Year;
use App\TempUser;
use App\Mail\BossCreateWithPromoCode;
use App\Mail\ContactMessageCreate;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Session;

use App\Code;
use App\PromoCode;
use App\Message;
use App\Property;
use App\User;
use App\Discount;
use App\Promo;
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
            'privatePolicy',
            'regulations',
            'contactPageShow',
            'contactPageUpdate',
            'acceptTerms',
            'promoShow',
            'about'
        ]);
    }
    
    /**
     * Show the application main page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function welcome()
    {
//        $canShowProperties = Property::where('canShow', 1)->get();
        
        return view('welcome')->with([
//            'canShowProperties' => $canShowProperties,
            'showBanner' => $this->showPolicyBanner()
        ]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {        
        $user = User::where('id', auth()->user()->id)->first();
        
        
        
        
        
        if ($user->email == 'andrzej.sierocki@el-trans.com') 
        {
            return redirect()->route('map');
        }
        
        
        
        
        $route = 'home';
        
        if ($user->isAdmin !== null)
        {
            $route = 'home_admin';
            
        } else if ($user->isEmployee !== null) {
            
            $route = 'home_employee';
            
        } else if ($user->isBoss !== null) {
            
            $route = 'home_boss';
        }
        
        return view($route)->with([
            'user' => $user,
            'showBanner' => $this->showPolicyBanner()
        ]);
    }
    
    public function subscriptions()
    {
        return view('subscriptions')->with([
            'showBanner' => $this->showPolicyBanner()
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
    
    public function privatePolicy()
    {
        return view('private_policy')->with([
            'showBanner' => $this->showPolicyBanner()
        ]);
    } 
    
    public function regulations()
    {
        return view('regulations')->with([
            'showBanner' => $this->showPolicyBanner()
        ]);
    } 
    
    public function about()
    {
        return view('about')->with([
            'showBanner' => $this->showPolicyBanner()
        ]);
    } 
    
    public function contactPageShow()
    {
        return view('contact_page')->with([
            'showBanner' => $this->showPolicyBanner()
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
            
            \Mail::to('mark.korcz@gmail.com')->send(new ContactMessageCreate($message));

            return redirect()->route('welcome')->with('success', 'Wiadomość została wysłana!');
        }
    }
    
    private function showPolicyBanner()
    {
        $confirmation = PolicyConfirmation::where('ip_address', $_SERVER['REMOTE_ADDR'])->first();
        
        return $confirmation !== null ? false : true; 
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
    
    public function promoShow()
    {
        $promotions = Promo::where('is_active', 1)->with('promoCodes')->get();
        $code = '';
        
        if (count($promotions) > 0)
        {
            $promo = $promotions->last();
            
            if (count($promo->promoCodes) > 0)
            {
                $freeCodeExistence = false;
                
                foreach ($promo->promoCodes as $promoCode)
                {
                    if ($promoCode->isActive == 0)
                    {
                        $freeCodeExistence = true;
                    }
                }
                
                $promo['freeCodeExistence'] = $freeCodeExistence;
                $code = $promo->promoCodes->first()->code;
            }
            
            return view('promo.show_public')->with([
                'promo' => $promo,
                'code' => $code
            ]);
        }
        
        return redirect()->route('welcome');
    }
    
    
    
    
    
    
    public function bioHome() {
        
        return view('bio.home');
    }
    
    public function bioContactMessage()
    {
        
        dd(Input::all());
        
        $rules = array(
            'name'        => 'required',
            'email'       => 'required|email',
            'topic'       => 'required|string',
            'description' => 'required|string'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return Redirect::to('/bio/home')
                ->withErrors($validator);
        } else {
            
//            $message = new Message();
//            $message->topic = Input::get('topic');
//            $message->email = Input::get('email');
//            $message->text  = Input::get('message');     
//            $message->save();
//            
//            \Mail::to('mark.korcz@gmail.com')->send(new ContactMessageCreate($message));
//
//            return redirect()->route('welcome')->with('success', 'Wiadomość została wysłana!');
        }
    }
    
    public function bioReservation() {
        
        return view('bio.reservation');
    }
    
    
    
//    public function test()
//    {
//        $filesDirPath = storage_path('app/notes');        
//        $filesDir = scandir($filesDirPath);
//        
//        $textFile = storage_path('app') . "/text.txt";
//        
//        // 1. I used this to change files extension to zip
//        for ($i = 2; $i < count($filesDir); $i++)
//        {
//            $directory = scandir($filesDirPath . "/" . $filesDir[$i]);
//            
//            if (count($directory) > 2)
//            {
//                $oldFileName = $filesDirPath . "/" . $filesDir[$i] . "/" . $directory[count($directory) - 1];              
//                $newFileName = $filesDirPath . "/" . $filesDir[$i] . "/new.zip";
//                rename($oldFileName, $newFileName);
//            }
//        }
//        
//        // 2. this to extract files
//        for ($i = 2; $i < count($filesDir); $i++)
//        {
//            $directory = scandir($filesDirPath . "/" . $filesDir[$i]);
//            
//            if (count($directory) > 2)
//            {
//                $fileName = $filesDirPath . "/" . $filesDir[$i] . "/new.zip";
//
//                $zip = new \ZipArchive;
//                if ($zip->open($fileName) === TRUE) {
//                    $zip->extractTo($filesDirPath . "/" . $filesDir[$i]);
//                    $zip->close();
//                    echo 'ok';
//                } else {
//                    echo 'failed - ' . $fileName;
//                }
//            }
//        }
//        
//        // 3. convert jlqm to json
//        for ($i = 2; $i < count($filesDir); $i++)
//        {
//            $directory = scandir($filesDirPath . "/" . $filesDir[$i]);
//            
//            if (count($directory) > 2)
//            {
//                $oldFileName = $filesDirPath . "/" . $filesDir[$i] . "/memoinfo.jlqm";
//                $newFileName = $filesDirPath . "/" . $filesDir[$i] . "/memoinfo.json";
//                rename($oldFileName, $newFileName);
//            }
//        }
//        
//        // 4. read note text
//        for ($i = 2; $i < count($filesDir); $i++)
//        {
//            $directory = scandir($filesDirPath . "/" . $filesDir[$i]);
//            
//            if (count($directory) > 2)
//            {
//                $fileName = $filesDirPath . "/" . $filesDir[$i] . "/memoinfo.json";
//                $file = File::get($fileName);
//                $decodedFile = json_decode($file);
//                $memoObjectList = $decodedFile->MemoObjectList;
//                $note = $memoObjectList[0]->DescRaw;
//
//                // and this to write all notes to one file
//                $current = file_get_contents($textFile);
//                $current .= $note . "\n\n";
//                file_put_contents($textFile, $current);
//            }
//        }
//    }
        
    
//    public function test()
//    {
//        $hash = '$2y$10$8R3OnzYV7pgIsLvCbRi1.eGMpjYZ.HtEbau5Gqry6YdlE9yxuq2vq';
//        
////        $employee = new User();
////        $employee->name = 'Marek5';
////        $employee->surname = 'Korcz';
////        $employee->slug = 'Marek5 Korcz';
////        $employee->phone_number = '729364873';
////        $employee->email = 'mark5.korcz@gmail.com';
////        $employee->password = '$2y$10$8R3OnzYV7pgIsLvCbRi1.eGMpjYZ.HtEbau5Gqry6YdlE9yxuq2vq';
////        $employee->isEmployee = 1;
////        $employee->save();
//        
////        $employee1 = User::create([
////            'name' => 'Marek5',
////            'surname' => 'Korcz',
////            'slug' => str_slug('Marek5 Korcz'),
////            'phone_number' => '729364873',
////            'email' => 'mark5.korcz@gmail.com',
////            'password' => '$2y$10$8R3OnzYV7pgIsLvCbRi1.eGMpjYZ.HtEbau5Gqry6YdlE9yxuq2vq',
////            'isEmployee' => 1
////        ]);
//    }
}
