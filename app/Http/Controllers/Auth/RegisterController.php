<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\TempUser;
use App\Property;
use App\TempProperty;
use App\Year;
use App\Month;
use App\Day;
use App\Code;
use App\PromoCode;
use App\Message;
use App\Mail\AdminTempBossCreate2ndStep;
use App\Mail\AdminTempEmployeeCreate2ndStep;
use App\Mail\UserCreateWithPromoCode;
use App\Mail\BossCreateWithPromoCode;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Foundation\Auth\RegistersUsers;
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
            'password' => ['required', 'string', 'min:7']
        ]);
    }

    /**
     * Create boss worker with code.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {        
        $code = Code::where('code', htmlentities($data['code'], ENT_QUOTES, "UTF-8"))->with('boss')->first();
        
        if ($code !== null)
        {
            $boss = $code->boss;
            
            $user = new User();
            $user->name = $data['name'];
            $user->surname = $data['surname'];
            $user->phone_number = $data['phone_number'];
            $user->email = $data['email'];
            $user->password = Hash::make($data['password']);
            $user->boss_id = $boss->id;
            $user->save();
            
            \Mail::to($user)->send(new UserCreateWithPromoCode($user, $boss));
            
            return $user;
        }
        
        return redirect()->route('welcome')->with('error', 'Rejestracja nie powiodła się');
    }
    
    /**
     * Creates a view to create boss from TempUser
     * 
     * @param type $code
     */
    public function tempUserBossRegistrationCreate($code)
    {     
        $bossRegisterCode = htmlentities($code, ENT_QUOTES, "UTF-8");
        
        if ($bossRegisterCode !== null)
        {
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
            'street'                => 'required|min:3',
            'street_number'         => 'required',
            'house_number'          => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return Redirect::to('temp-boss/register/' . Input::get('register_code'))
                ->withErrors($validator);
        } else {
            
            $tempBossEntity = TempUser::where([
                'register_code' => Input::get('register_code'),
                'isBoss' => 1
            ])->first();
            
            $tempBossPropertyEntity = TempProperty::where('temp_user_id', $tempBossEntity->id)->first();
            
            if ($tempBossEntity !== null && $tempBossPropertyEntity !== null)
            {            
                $boss = new User();
                $boss->name = Input::get('name');
                $boss->surname = Input::get('surname');
                $boss->email = Input::get('boss_email');
                $boss->phone_number = Input::get('boss_phone_number');
                $boss->password = Hash::make(Input::get('password'));
                $boss->isBoss = 1;
                $boss->save();

                if ($boss !== null)
                {
                    $property = new Property();
                    $property->name = Input::get('property_name');
                    $property->slug = str_slug(Input::get('property_name'));
                    $property->street = Input::get('street');
                    $property->street_number = Input::get('street_number');
                    $property->house_number = Input::get('house_number');
                    $property->city = "Warszawa";
                    $property->boss_id = $boss->id;
                    $property->save();

                    if ($property !== null)
                    {                        
                        // delete temporary entities
                        $tempBossEntity->delete();
                        $tempBossPropertyEntity->delete();

                        $this->addCalendarToPropery($property->id, 12);

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
     * Handle storing new boss with promo code
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
                'is_active' => 0
            ])->with([
                'promo'
            ])->first();
            
            if ($promoCode !== null)
            {                
                if ($promoCode->promo->is_active == 0 || $promoCode->promo->available_code_count <= 0 || $promoCode->is_active !== 0)
                {
                    return redirect('/register')->with([
                        'error' => 'Przepraszamy, czas promocji dobiegł końca!'
                    ]);
                }
                
                $boss = new User();
                $boss->name = Input::get('name');
                $boss->surname = Input::get('surname');
                $boss->phone_number = Input::get('phone_number');
                $boss->email = Input::get('email');
                $boss->password = Hash::make(Input::get('password'));
                $boss->isBoss = 1;
                $boss->is_approved = 0;
                $boss->save();

                if ($boss !== null)
                {     
                    $bossProperty = new Property();
                    $bossProperty->name = Input::get('property_name');
                    $bossProperty->slug = str_slug(Input::get('property_name'));
                    $bossProperty->street = Input::get('street');
                    $bossProperty->street_number = Input::get('street_number');
                    $bossProperty->house_number = Input::get('house_number');
                    $bossProperty->city = Input::get('city');
                    $bossProperty->boss_id = $boss->id;
                    $bossProperty->save();

                    if ($bossProperty !== null)
                    {
                        $this->addCalendarToPropery($bossProperty->id, 12);
                         
                        $promoCode->activation_date = date('Y-m-d H:i:s');
                        $promoCode->is_active = 1;
                        $promoCode->boss_id = $boss->id;
                        $promoCode->save();
                        
                        $promoCode->promo->available_code_count = $promoCode->promo->available_code_count - 1;
                        $promoCode->promo->used_code_count = $promoCode->promo->used_code_count + 1;
                        $promoCode->promo->save();
                        
                        // send initial message to promocode user
                        $admin = User::where('isAdmin', 1)->first();
                        $splitedBossName = str_split($boss->name);
                        $youUsedPhrase = $splitedBossName[count($splitedBossName) - 1] == "a" ? \Lang::get('common.you_used_female') : \Lang::get('common.you_used_male');
                        
                        $message = new Message();
                        $message->text = \Lang::get('common.greetings') . ", " . $boss->name . " " . $boss->surname . "! " . 
                            \Lang::get('common.we_are_very_happy_that') . " " . $youUsedPhrase . " " . 
                            \Lang::get('common.approve_message_body') . " " . 
                            config('app.name') . " " .
                            config('app.name_2nd_part');
                        $message->status = 0;
                        $message->user_id = $admin->id;
                        $message->promo_code_id = $promoCode->id;
                        $message->save();
                        
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
    
    /**
     * Creates a view to create employee from TempUser
     * 
     * @param string $code
     */
    public function tempUserEmployeeRegistrationCreate($code)
    {             
        $employeeRegisterCode = htmlentities($code, ENT_QUOTES, "UTF-8");
        
        if ($employeeRegisterCode !== null)
        {
            $employee = TempUser::where('register_code', $employeeRegisterCode)->first();

            if ($employee !== null)
            {
                return view('auth.temp_user_employee_register')->with([
                    'tempUser' => $employee,
                    'registerCode' => $employeeRegisterCode
                ]);
            }
        }
        
        return redirect()->route('login');
    }
    
    /**
     * Handle storing employee made from TempUser
     */
    public function tempUserEmployeeRegistrationStore()
    {
        $rules = array(
            'name'                  => 'required|string|min:4|max:24',
            'surname'               => 'required|string|min:3|max:24',
            'email'                 => 'required|string|email|unique:users,email|max:33',
            'phone_number'          => 'required|numeric|regex:/[0-9]/|min:7',
            'password'              => 'required|min:7|confirmed'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return Redirect::to('temp-employee/register/' . Input::get('register_code'))
                ->withErrors($validator);
        } else {
            
            $tempEmployeeEntity = TempUser::where([
                'register_code' => Input::get('register_code'),
                'isEmployee' => 1
            ])->first();
            
            if ($tempEmployeeEntity !== null)
            {
                $employee = User();
                $employee->name = Input::get('name');
                $employee->surname = Input::get('surname');
                $employee->slug = str_slug(Input::get('name') . "_" . Input::get('surname'));
                $employee->email = Input::get('email');
                $employee->phone_number = Input::get('phone_number');
                $employee->password = Hash::make(Input::get('password'));
                $employee->isEmployee = 1;
                $employee->save();

                if ($employee !== null)
                {
                    $tempEmployeeEntity->delete();
                        
                    \Mail::to($employee)->send(new AdminTempEmployeeCreate2ndStep($employee));

                    auth()->login($employee);

                    return redirect()->route('home')->with('success', 'Gratulacje, Twoje konto zostało utworzone!');
                }
                
                return redirect('/temp-employee/register')->with([
                    'error' => 'Coś poszło nie tak',
                    'code' => Input::get('register_code')
                ]);
            }
            
            return redirect()->route('welcome');
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
    
    private function addCalendarToPropery($propertyId, $numberOfMonths)
    {        
        $currentYear = new Year();
        $currentYear->year = date('Y');
        $currentYear->property_id = $propertyId;
        $currentYear->save();

        $currentYearIncrementedByOneYear = date('Y', strtotime("+1 year", strtotime(date('Y'))));
        
        $nextYear = new Year();
        $nextYear->year = $currentYearIncrementedByOneYear;
        $nextYear->property_id = $propertyId;
        $nextYear->save();

        $today = date('Y-n');                

        for ($i = 1; $i <= $numberOfMonths; $i++)
        {                    
            $monthName = "";
            $monthNameEn = "";
            $todayInParts = explode("-", $today);
            $numberOfDaysInMonth = cal_days_in_month(CAL_GREGORIAN, (int)$todayInParts[1], (int)$todayInParts[0]);

            switch ((int)$todayInParts[1]) 
            {
                case 1:
                    $monthName = "Styczeń";
                    $monthNameEn = "January";
                    break;
                case 2:
                    $monthName = "Luty";
                    $monthNameEn = "February";
                    break;
                case 3:
                    $monthName = "Marzec";
                    $monthNameEn = "March";
                    break;
                case 4:
                    $monthName = "Kwiecień";
                    $monthNameEn = "April";
                    break;
                case 5:
                    $monthName = "Maj";
                    $monthNameEn = "May";
                    break;
                case 6:
                    $monthName = "Czerwiec";
                    $monthNameEn = "June";
                    break;
                case 7:
                    $monthName = "Lipiec";
                    $monthNameEn = "July";
                    break;
                case 8:
                    $monthName = "Sierpień";
                    $monthNameEn = "August";
                    break;
                case 9:
                    $monthName = "Wrzesień";
                    $monthNameEn = "September";
                    break;
                case 10:
                    $monthName = "Październik";
                    $monthNameEn = "October";
                    break;
                case 11:
                    $monthName = "Listopad";
                    $monthNameEn = "November";
                    break;
                case 12:
                    $monthName = "Grudzień";
                    $monthNameEn = "December";
                    break;
            }
            
            $month = new Month();
            $month->month = $monthName;
            $month->month_en = $monthNameEn;
            $month->month_number = $todayInParts[1];
            $month->days_in_month = $numberOfDaysInMonth;
            $month->year_id = $todayInParts[0] == date("Y") ? $currentYear->id : $nextYear->id;
            $month->save();

            if ($month !== null)
            {
                $month->load('year');
                $year = $month->year;
                
                $monthNumber = strlen($month->month_number) == 2 ? $month->month_number : "0" . $month->month_number;
                
                for ($j = 1; $j <= $numberOfDaysInMonth; $j++)
                {
                    $dayNumber = strlen($j) == 2 ? $j : "0" . $j;
                    $dayDate = new \DateTime($year->year . "-" . $monthNumber . "-" . $dayNumber);

                    if ($dayDate->format("N") != 7)
                    {                                
                        $day = new Day();
                        $day->day_number = $dayDate->format("j");
                        $day->number_in_week = $dayDate->format("N");
                        $day->month_id = $month->id;
                        $day->save();
                    }
                }
            }

            $today = date('Y-m-d', strtotime("+1 month", strtotime($today)));
        }
    }
}
