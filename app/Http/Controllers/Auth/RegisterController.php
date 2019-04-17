<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Code;
use App\ChosenProperty;
use App\Purchase;
use App\Interval;
use App\Substart;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Collection;

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
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'numeric', 'regex:/[0-9]{9}/'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
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
                        $userChosenProperty->property_id = $bossChosenProperty->id;
                        $userChosenProperty->save();
                        
                        foreach ($bossChosenProperty->subscriptions as $subscription)
                        {
                            $substart = Substart::where([
                                'boss_id' => $code->boss_id,
                                'subscription_id' => $subscription->id,
                                'property_id' => $bossChosenProperty->property_id
                            ])->first();
                            
                            $now = new \DateTime();
                            
                            if ($substart !== null && $substart->start_date <= $now && $substart->end_date > $now)
                            {
                                $purchase = new Purchase();
                                $purchase->subscription_id = $subscription->id;
                                $purchase->chosen_property_id = $userChosenProperty->id;                            
                                $purchase->save();
                                
                                if ($substart->isActive)
                                {
                                    $startDate = $substart->start_date;
                                    
                                    for ($i = 1; $i <= $subscription->duration; $i++)
                                    {
                                        $interval = new Interval();
                                        $interval->available_units = $subscription->quantity;

                                        $interval->start_date = $startDate;
                                        $startDate = date('Y-m-d', strtotime("+1 month", strtotime($startDate)));

                                        $endDate = date('Y-m-d', strtotime("-1 day", strtotime($startDate)));
                                        $interval->end_date = $endDate;

                                        $interval->purchase_id = $purchase->id;
                                        $interval->save();
                                    }
                                    
                                } else {
                                    
                                    $startDate = date('Y-m-d');
                                    
                                    $interval = new Interval();
                                    $interval->available_units = $subscription->quantity * $subscription->duration;

                                    $interval->start_date = $startDate;
                                    $startDate = date('Y-m-d', strtotime("+" . ($subscription->duration - 1) . " month", strtotime($startDate)));

                                    $endDate = date('Y-m-d', strtotime("-1 day", strtotime($startDate)));
                                    $interval->end_date = $endDate;

                                    $interval->purchase_id = $purchase->id;
                                    $interval->save();
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
}
