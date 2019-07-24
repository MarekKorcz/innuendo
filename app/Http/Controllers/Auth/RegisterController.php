<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\TempUser;
use App\Property;
use App\TempProperty;
use App\Code;
use App\ChosenProperty;
use App\Purchase;
use App\Interval;
use App\Substart;
use App\PromoCode;
use App\Mail\AdminTempBossCreate2ndStep;
use App\Mail\BossCreateWithPromoCode;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Redirect;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'min:4', 'max:255'],
            'surname' => ['required', 'string', 'min:3', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'code' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'numeric', 'regex:/[0-9]/', 'min:7'],
            'password' => ['required', 'string', 'min:7', 'confirmed']
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'surname' => $data['surname'],
            'phone_number' => $data['phone_number'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
        
        if ($user && is_string($data['code']))
        {
            $code = Code::where('code', htmlentities($data['code'], ENT_QUOTES, "UTF-8"))->with('chosenProperties')->first();
                        
            if ($code !== null)
            {
                // register boss workers scenario
                $user->boss_id = $code->boss_id;

                $bossCodeChosenProperties = new Collection();

                if ($code->chosenProperties !== null)
                {
                    foreach ($code->chosenProperties as $chosenProperty)
                    {
                        $chosenProperty = ChosenProperty::where('id', $chosenProperty->id)->with('subscriptions')->first();
                        $bossCodeChosenProperties->push($chosenProperty);
                    }
                }

                if (count($bossCodeChosenProperties))
                {
                    foreach ($bossCodeChosenProperties as $bossChosenProperty)
                    {
                        $userChosenProperty = new ChosenProperty();
                        $userChosenProperty->user_id = $user->id;
                        $userChosenProperty->property_id = $bossChosenProperty->property_id;
                        $userChosenProperty->save();

                        foreach ($bossChosenProperty->subscriptions as $subscription)
                        {
                            $substarts = Substart::where([
                                'boss_id' => $code->boss_id,
                                'subscription_id' => $subscription->id,
                                'property_id' => $bossChosenProperty->property_id
                            ])->get();

                            if (count($substarts) > 0)
                            {                                
                                $today = new \DateTime(date('Y-m-d'));

                                foreach ($substarts as $substart)
                                {
                                    if ($substart->start_date <= $today && $substart->end_date > $today)
                                    {
                                        $purchase = new Purchase();
                                        $purchase->subscription_id = $subscription->id;
                                        $purchase->chosen_property_id = $userChosenProperty->id;                       
                                        $purchase->substart_id = $substart->id;                       
                                        $purchase->save();

                                        $startDate = $substart->start_date;

                                        if ($substart->isActive)
                                        {
                                            for ($i = 1; $i <= $subscription->duration; $i++)
                                            {
                                                $bossInterval = Interval::where([
                                                    'start_date' => $startDate,
                                                    'substart_id' => $substart->id
                                                ])->first();

                                                $interval = new Interval();
                                                $interval->available_units = $subscription->quantity;

                                                $interval->start_date = $startDate;
                                                $startDate = date('Y-m-d', strtotime("+1 month", strtotime($startDate)));

                                                $endDate = date('Y-m-d', strtotime("-1 day", strtotime($startDate)));
                                                $interval->end_date = $endDate;

                                                $interval->interval_id = $bossInterval->id;
                                                $interval->purchase_id = $purchase->id;
                                                $interval->save();
                                            }

                                        } else {

                                            $bossInterval = Interval::where([
                                                'start_date' => $startDate,
                                                'end_date' => $substart->end_date,
                                                'substart_id' => $substart->id
                                            ])->first();

                                            $interval = new Interval();
                                            $interval->available_units = $subscription->quantity * $subscription->duration;

                                            $interval->start_date = $bossInterval->start_date;
                                            $interval->end_date = $bossInterval->end_date;

                                            $interval->interval_id = $bossInterval->id;
                                            $interval->purchase_id = $purchase->id;
                                            $interval->save();
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        
        $user->save();
        
        return $user;
    }
    
    /**
     * Creates a view to create boss from TempUser
     * 
     * @param type $code
     */
    public function tempUserBossRegistrationCreate($code)
    {     
        $bossRegisterCode = htmlentities($code, ENT_QUOTES, "UTF-8");
        $boss = TempUser::where('register_code', $bossRegisterCode)->first();
        
        if ($boss !== null)
        {
            $property = TempProperty::where('temp_user_id', $boss->id)->first();
            
            if ($property !== null)
            {
                return view('auth.temp_user_boss_register')->with([
                    'tempUser' => $boss,
                    'tempProperty' => $property,
                    'registerCode' => $bossRegisterCode
                ]);
            }
        }
        
        return redirect()->route('login');
    }
    
    /**
     * Handle storing boss made from TempUser
     */
    public function tempUserBossRegistrationStore()
    {
        $rules = array(
            'name'                  => 'required|string|min:4|max:24',
            'surname'               => 'required|string|min:3|max:24',
            'boss_email'            => 'required|string|email|unique:users,email|max:33',
            'boss_phone_number'     => 'required|numeric|regex:/[0-9]/|min:7',
            'password'              => 'required|min:7|confirmed',
            'property_name'         => 'required|min:3',
//            'property_email'        => 'required|email|unique:properties,email',
//            'property_phone_number' => 'required|numeric|regex:/[0-9]/|min:7',
            'street'                => 'required|min:3',
            'street_number'         => 'required',
            'house_number'          => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return Redirect::to('temp-boss/register/' . Input::get('register_code'))
                ->withErrors($validator);
        } else {
            
            $tempBossEntity = TempUser::where('register_code', Input::get('register_code'))->first();
            $tempBossPropertyEntity = TempProperty::where('temp_user_id', $tempBossEntity->id)->with('subscriptions')->first();
            
            if ($tempBossEntity !== null && $tempBossPropertyEntity)
            {
                $boss = User::create([
                    'name' => Input::get('name'),
                    'surname' => Input::get('surname'),
                    'email' => Input::get('boss_email'),
                    'phone_number' => Input::get('boss_phone_number'),
                    'password' => Hash::make(Input::get('password')),
                    'isBoss' => 1
                ]);

                if ($boss !== null)
                {
                    $property = Property::create([
                        'name' => Input::get('property_name'),
                        'slug' => str_slug(Input::get('property_name')),
                        'street' => Input::get('street'),
                        'street_number' => Input::get('street_number'),
                        'house_number' => Input::get('house_number'),
                        'city' => "Warszawa",
                        'boss_id' => $boss->id
                    ]);

                    if ($property !== null)
                    {
                        if ($tempBossPropertyEntity->subscriptions !== null)
                        {
                            foreach ($tempBossPropertyEntity->subscriptions as $subscription)
                            {
                                $property->subscriptions()->attach($subscription);
                                $tempBossPropertyEntity->subscriptions()->detach($subscription);
                            }
                        }
                        
                        $tempBossEntity->delete();
                        $tempBossPropertyEntity->delete();
                        
                        \Mail::to($boss)->send(new AdminTempBossCreate2ndStep($boss));
                        
                        auth()->login($boss);

                        return redirect()->route('home')->with('success', 'Gratulacje, Twoje konto wraz z lokalizacją zostały stworzone!');

                    } else {

                        $boss->delete();
                    }
                }
                
                return redirect('/temp-boss/register')->with([
                    'error' => 'Coś poszło nie tak',
                    'code' => Input::get('register_code')
                ]);
            }
            
            return redirect()->route('welcome');
        }
    }
    
    /**
     * Handle storing new boss
     */
    public function registerNewBoss()
    {
        $rules = array(
            'name'           => 'required|string|min:4|max:255',
            'surname'        => 'required|string|min:3|max:255',
            'email'          => 'required|string|email|max:255|unique:users',
            'code'           => 'required|string|max:255',
            'phone_number'   => 'required|numeric|regex:/[0-9]/|min:7',
            'password'       => 'required|string|min:7',
            'property_name'  => 'required|string|min:3',
            'street'         => 'required|string|min:3',
            'street_number'  => 'required',
            'house_number'   => 'required',
            'city'           => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return Redirect::to('/register')
                ->withErrors($validator);
        } else {
            
            $promoCode = PromoCode::where([
                'code' => Input::get('code'),
                'isActive' => 0
            ])->with('subscriptions')->first();

            if ($promoCode !== null)
            {
                $boss = User::create([
                    'name' => Input::get('name'),
                    'surname' => Input::get('surname'),
                    'phone_number' => Input::get('phone_number'),
                    'email' => Input::get('email'),
                    'password' => Hash::make(Input::get('password')),
                    'isBoss' => 1,
                    'isApproved' => 0
                ]);

                if ($boss !== null)
                {     
                    $bossProperty = Property::create([
                        'name' => Input::get('property_name'),
                        'slug' => str_slug(Input::get('property_name')),
                        'street' => Input::get('street'),
                        'street_number' => Input::get('street_number'),
                        'house_number' => Input::get('house_number'),
                        'city' => Input::get('city'),
                        'boss_id' => $boss->id
                    ]);

                    if ($bossProperty !== null)
                    {
                        $bossChosenProperty = ChosenProperty::create([
                            'user_id' => $boss->id,
                            'property_id' => $bossProperty->id
                        ]);

                        if (count($promoCode->subscriptions) > 0)
                        {
                            foreach ($promoCode->subscriptions as $subscription)
                            {
                                $bossProperty->subscriptions()->attach($subscription->id);
                                $bossChosenProperty->subscriptions()->attach($subscription->id);
                                        
                                $bossPurchase = Purchase::create([
                                    'subscription_id' => $subscription->id,
                                    'chosen_property_id' => $bossChosenProperty->id
                                ]);

                                $startDate = new \DateTime(date('Y-m-d'));

                                $substart = new Substart();
                                $substart->start_date = $startDate;

                                $startDateIncrementedBySubscriptionDuration = date('Y-m-d', strtotime("+" . $subscription->duration . " month", strtotime($startDate->format("Y-m-d"))));
                                $endDate = date('Y-m-d', strtotime("-1 day", strtotime($startDateIncrementedBySubscriptionDuration)));

                                $substart->end_date = $endDate;
                                $substart->boss_id = $boss->id;
                                $substart->property_id = $bossProperty->id;
                                $substart->subscription_id = $subscription->id;
                                $substart->purchase_id = $bossPurchase->id;
                                $substart->save();
                                
                                $bossPurchase->substart_id = $substart->id;
                                $bossPurchase->save();

                                $interval = Interval::create([
                                    'available_units' => $subscription->quantity * $subscription->duration,
                                    'start_date' => $substart->start_date,
                                    'end_date' => $substart->end_date,
                                    'substart_id' => $substart->id,
                                    'purchase_id' => $bossPurchase->id
                                ]);
                            }
                        }                    

                        $promoCode->activation_date = date('Y-m-d H:i:s');
                        $promoCode->isActive = 1;
                        $promoCode->boss_id = $boss->id;
                        $promoCode->save();
                        
                        \Mail::to($boss)->send(new BossCreateWithPromoCode($boss));
                        
                        auth()->login($boss);

                        return redirect()->route('home')->with('success', 'Gratulacje, Twoje konto wraz z lokalizacją oraz pakietem promocyjnych masaży, zostało stworzone!');

                    } else {

                        $boss->delete();
                    }
                }

                return redirect('/register')->with([
                    'error' => 'Istnieje już użytkownik o podanym adresie email'
                ]);
            }

            return redirect('/register')->with([
                'error' => 'Przepraszamy, wszystkie kody promocyjne zostały już wykorzystane!'
            ]);
        }
    }
    
    public function checkIfCodeExists(Request $request)
    {        
        if ($request->get('code'))
        {
            $codeEntity = Code::where('code', $request->get('code'))->first();
            $promoCodeEntity = PromoCode::where('code', $request->get('code'))->first();
            
            if ($codeEntity !== null)
            {                       
                $data = [
                    'status' => 'existing',
                    'for' => 'worker'
                ];
                
            } else if ($promoCodeEntity !== null) {
                
                $data = [
                    'status' => 'existing',
                    'for' => 'boss'
                ];
            
            } else {
                
                $data = [
                    'status' => 'notExisting'
                ];
            }
            
            return new JsonResponse($data, 200, array(), true);
        }
        
        return new JsonResponse(array(
            'type'    => 'error',
            'message' => 'Pusty request'            
        ));
    }
}
