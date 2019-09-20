<?php

namespace App\Http\Controllers;

use App\User;
use App\TempUser;
use App\TempProperty;
use App\GraphicRequest;
use App\Message;
use App\Calendar;
use App\Property;
use App\Subscription;
use App\Promo;
use App\PromoCode;
use App\Year;
use App\Month;
use App\Mail\AdminTempBossCreate;
use App\Mail\AdminTempEmployeeCreate;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Redirect;

class AdminController extends Controller
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
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function userList()
    {
        $users = User::where([
            'isAdmin' => null,
            'isBoss' => null,
            'isEmployee' => null
        ])->orderBy('created_at', 'desc')->get();
        
        $tempUsers = TempUser::where('isBoss', 0)->orderBy('created_at', 'desc')->get();

        return view('admin.user_list')->with([
            'users' => $users,
            'tempUsers' => $tempUsers
        ]);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function bossList()
    {
        $bosses = User::where('isBoss', '!=', null)->orderBy('created_at', 'desc')->get();
        $tempBosses = TempUser::where('isBoss', 1)->orderBy('created_at', 'desc')->get();

        return view('admin.boss_list')->with([
            'bosses' => $bosses,
            'tempBosses' => $tempBosses
        ]);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function employeeList()
    {
        $employees = User::where('isEmployee', '!=', null)->orderBy('created_at', 'desc')->get();
        $tempEmployees = TempUser::where('isEmployee', 1)->orderBy('created_at', 'desc')->get();

        return view('admin.employee_list')->with([
            'employees' => $employees,
            'tempEmployees' => $tempEmployees
        ]);
    }
    
    /**
     * Display specific resource.
     *
     * @param integer $id
     * @return Response
     */
    public function userShow($id)
    {
        if ($id !== null)
        {
            $user = User::where('id', $id)->with('chosenProperties')->first();
            
            // todo: (na póżniej) wyświetl wszystkie wykupione subskrypcje (z podziałem na prywatne i publiczne) + zrób button do wyświetlania tych wizyt
            // zrób button do wyświetlania wszystkich wizyt
            
//            dump($user);die;
            
            $properties = new Collection();
            
            if ($user->chosenProperties)
            {
                foreach ($user->chosenProperties as $chosenProperty)
                {
                    $property = Property::where('id', $chosenProperty->property_id)->first();

                    if ($property->boss_id !== 0 || $property->boss_id !== null)
                    {
                        $owner = User::where('id', $property->boss_id)->first();
                        
                        if ($owner !== null)
                        {
                            $property['owner'] = $owner;
                            
                        } else {
                            
                            $property['owner'] = 'public';
                        }
                        
                    } else {
                        
                        $property['owner'] = 'public';
                    }
                    
                    
                    
                    $properties->push($property);
                }
            }
            
            // todo: (na póżniej) zrób tak żeby były wyświetlone dane user'a z buttonami do zmiany jego danych
            // na dole lista propertisów w któych wykupione subskrypcje wraz z listą subskrypcji zaczętych z buttonami do widoków z wizytami
            // dodaj jeszcze button z wszystkimi wizytami w danych okresach
            dump($properties);die;

            return view('admin.user_show')->with([
                'user' => $user
            ]);
        }
        
        return redirect()->route('welcome');
    }
    
    /**
     * Display specific resource.
     *
     * @param integer $id
     * @return Response
     */
    public function bossShow($id)
    {
        if ($id !== null)
        {
            $boss = User::where([
                'id' => $id,
                'isBoss' => 1
            ])->first();
            
            $boss['promoCode'] = null;
            $bossPromoCode = PromoCode::where('boss_id', $boss->id)->first();

            if ($bossPromoCode !== null)
            {
                $boss['promoCode'] = $bossPromoCode;
            }

            return view('admin.boss_show')->with([
                'boss' => $boss,
                'properties' => $boss->getPlaces()
            ]);
        }
        
        return redirect()->route('welcome');
    }
    
    /**
     * Display specific resource.
     *
     * @param integer $id
     * @return Response
     */
    public function tempUserBossShow($id)
    {
        if ($id !== null)
        {
            $tempBoss = TempUser::where([
                'id' => $id,
                'isBoss' => 1
            ])->with('tempProperty')->first();
            
            return view('admin.temp_user_boss_show')->with([
                'tempBoss' => $tempBoss,
                'tempProperty' => $tempBoss->tempProperty
            ]);
        }
        
        return redirect()->route('welcome');
    }
    
    /**
     * Display specific resource.
     *
     * @param integer $id
     * @return Response
     */
    public function tempUserUserShow($id)
    {
        if ($id !== null)
        {
            $boss = TempUser::where([
                'id' => $id,
                'isBoss' => 0
            ])->with('appointments')->first();
            
            dump($boss);die;

            // zrób ten widok
//            return view('admin.temp_user_user_show')->with([
//                'boss' => $boss
//            ]);
        }
        
        return redirect()->route('welcome');
    }
    
    /**
     * Show create employee form.
     */
    public function employeeCreate()
    {
        return view('admin.employee_create');
    }
    
    /**
     * Add employee.
     */
    public function employeeAdd()
    {
        $rules = array(
            'name'           => 'required',
            'surname'        => 'required',
            'email'          => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return Redirect::to('admin/employee/list')
                ->withErrors($validator);
        } else {
            
            $employee = new TempUser();
            $employee->name = Input::get('name');
            $employee->surname = Input::get('surname');
            $employee->email = Input::get('email');
            
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $codeText = "";

            for ($i = 0; $i < 20; $i++) 
            {
                $codeText .= $characters[rand(0, $charactersLength - 1)];
            }
                
            $employee->register_code = $codeText;
            $employee->isEmployee = 1;
            $employee->save();

            \Mail::to($employee)->send(new AdminTempEmployeeCreate($employee));

            return redirect('admin/employee/list/')->with('success', 'Employee temporary entity has been successfully added!');
        }
    }
    
    /**
     * Manually send activation email to employee.
     * 
     * @param type $id
     * @return type
     */
    public function tempUserEmployeeSendActivationEmail($id)
    {
        $employee = TempUser::where([
            'id' => $id,
            'isEmployee' => 1
        ])->first();
            
        if ($employee !== null)
        {
            \Mail::to($employee)->send(new AdminTempEmployeeCreate($employee));

            return redirect('admin/employee/list')->with('success', 'Activation email has been sended to employee!');
        }
        
        return redirect()->route('welcome');
    }
    
    /**
     * Display specific resource.
     *
     * @param string $slug
     * @return Response
     */
    public function employeeShow($slug)
    {
        $employee = User::where([
            'isEmployee' => 1,
            'slug' => $slug
        ])->first();
        
        if ($employee !== null)
        {
            $user = auth()->user();
            
            $calendars = new Collection();
            $properties = [];
            
            if ($user !== null)
            {
                $calendars = Calendar::where([
                    'employee_id' => $employee->id,
                    'isActive' => 1
                ])->get();

                if ($user->isBoss) 
                {                
                    if (count($calendars) > 0)
                    {
                        foreach ($calendars as $key => $calendar)
                        {
                            if ($calendar->property->boss_id !== $user->id)
                            {
                                $calendars->forget($key);
                            }
                        }
                    }

                } else if ($user->boss_id !== null) {

                    $user = User::where('id', $user->id)->with('chosenProperties')->first();

                    $calendarsAvailableToWorker = new Collection();

                    if (count($user->chosenProperties) > 0 && count($calendars) > 0)
                    {
                        foreach ($calendars as $calendar)
                        {
                            foreach ($user->chosenProperties as $chosenProperty)
                            {
                                if ($calendar->property->id === $chosenProperty->property_id)
                                {
                                    $calendarsAvailableToWorker->push($calendar);
                                }
                            }
                        }
                    }

                    if (count($calendarsAvailableToWorker) > 0)
                    {
                        $calendars = new Collection();

                        foreach ($calendarsAvailableToWorker as $calendar)
                        {
                            $calendars->push($calendar);
                        }
                    }
                }

                for ($i = 0; $i < count($calendars); $i++)
                {
                    $properties[$i] = Property::where('id', $calendars[$i]->property_id)->first();
                }
            }
            
            $calendarsArray = [];

            if (count($calendars) > 0)
            {
                for ($i = 0; $i < count($calendars); $i++)
                {
                    $calendarsArray[$i + 1] = $calendars[$i];
                }
            }
            
            // todo: (na póżniej, będzie trzeba zrobić system wewnętrznego rozliczania się z pracownikami)

            return view('admin.employee_show')->with([
                'employee' => $employee,
                'calendars' => $calendarsArray,
                'properties' => $properties
            ]);
        }
        
        return redirect()->route('welcome');
    }
    
    /**
     * Updates employee data.
     *
     * @return Response
     */
    public function employeeUpdate(Request $request)
    {
        $rules = array(
            'name'           => 'required',
            'surname'        => 'required',
            'slug'           => 'required',
            'email'          => 'required',
            'phone_number'   => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return Redirect::to('admin/employee/show/' . Input::get('slug'))
                ->withErrors($validator);
        } else {
            
            $employee = User::where([
                'id' => Input::get('id'),
                'isEmployee' => 1
            ])->first();
            
            if ($employee !== null)
            {
                $employee->name = Input::get('name');
                $employee->surname = Input::get('surname');
                $employee->slug = Input::get('slug');
                $employee->email = Input::get('email');
                $employee->phone_number = Input::get('phone_number');
                
                $file = $request->file('profile_image');
                
                if ($file)
                {
                    $fileName = $request->get('name') . '_' . $request->get('surname') . '_' . time() . '.jpg';
                    
                    Storage::disk('local')->put($fileName, File::get($file));
                    
                    $employee->profile_image = $fileName;
                }
                
                $employee->save();

                return redirect('admin/employee/show/' . $employee->slug)->with('success', 'Employee entity has been successfully updated!');
            }
        }
    }
    
    /**
     * Edits specific resource.
     *
     * @return Response
     */
    public function userEdit(Request $request)
    {
        dump($request->request->all());die;
    }
    
    /**
     * Display view to create boss account.
     *
     * @return Response
     */
    public function bossCreate()
    {
        return view('admin.boss_create');
    }
    
    /**
     * Create boss account with property.
     *
     * @return Response
     */
    public function bossStore()
    {
        $rules = array(
            'name'           => 'required',
            'surname'        => 'required',
            'street'         => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return Redirect::to('admin/boss/create')
                ->withErrors($validator);
        } else {
            
            $boss = new TempUser();
            $boss->name = Input::get('name');
            $boss->surname = Input::get('surname');
            $boss->email = Input::get('boss_email');
            $boss->phone_number = Input::get('boss_phone_number');
            
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $codeText = "";

            for ($i = 0; $i < 20; $i++) 
            {
                $codeText .= $characters[rand(0, $charactersLength - 1)];
            }
                
            $boss->register_code = $codeText;
            $boss->isBoss = 1;
            $boss->save();
            
            if ($boss !== null)
            {
                $property = new TempProperty();
                $property->name          = Input::get('property_name');
                $property->slug          = str_slug(Input::get('property_name'));
                $property->street        = Input::get('street');
                $property->street_number = Input::get('street_number');
                $property->house_number  = Input::get('house_number');
                $property->city          = "Warszawa";
                $property->temp_user_id  = $boss->id;
                $property->save();
                
                \Mail::to($boss)->send(new AdminTempBossCreate($boss));

                return redirect('/admin/boss/list')->with('success', 'Boss and property successfully created!');
            }
        }
    }
    
    public function graphicRequests()
    {
        $graphicRequests = GraphicRequest::where('id', '!=', null)->with([
            'property',
            'year',
            'month',
            'day',
            'employees'
        ])->get();

        foreach ($graphicRequests as $graphicRequest)
        {                
            if ($graphicRequest->comment !== null && strlen($graphicRequest->comment) > 24)
            {
                $graphicRequest->comment = substr($graphicRequest->comment, 0, 24).'...';
                
                $graphicRequest['boss'] = User::where('id', $graphicRequest->property->boss_id)->first();
            }
        }
        
        return view('admin.graphic_requests')->with([
            'graphicRequests' => $graphicRequests
        ]);
    }
    
    public function graphicRequestShow($graphicRequestId, $chosenMessageId = 0)
    {
        $graphicRequest = GraphicRequest::where('id', $graphicRequestId)->with([
            'property',
            'year',
            'month',
            'day',
            'employees'
        ])->first();
        
        if ($graphicRequest !== null)
        {
            $allEmployees = User::where('isEmployee', 1)->get();
            
            if (count($allEmployees) > 0)
            {
                foreach ($allEmployees as $employee)
                {                    
                    foreach ($graphicRequest->employees as $chosenEmployee)
                    {
                        if ($employee->id == $chosenEmployee->id)
                        {
                            $chosenEmployee = User::where('id', $employee->id)->with('calendars')->first();
                            
                            $employee['isChosen'] = true;

                            $employee['showProperty'] = false;
                            
                            $employee['yearId'] = null;
                            $employee['monthId'] = null;
                            $employee['dayId'] = null;
                            
                            if (count($chosenEmployee->calendars) > 0)
                            {
                                foreach ($chosenEmployee->calendars as $calendar)
                                {
                                    $calendar = Calendar::where('id', $calendar->id)->with('years')->first();

                                    if ($calendar !== null && $calendar->property_id == $graphicRequest->property_id && count($calendar->years) > 0)
                                    {
                                        foreach ($calendar->years as $year)
                                        {  
                                            if ($year->year == $graphicRequest->year->year)
                                            {
                                                $year = Year::where('id', $year->id)->with('months')->first();

                                                if (count($year->months) > 0)
                                                {
                                                    foreach ($year->months as $month)
                                                    {
                                                        if ($month->month_number == $graphicRequest->month->month_number)
                                                        {
                                                            $month = Month::where('id', $month->id)->with('days')->first();

                                                            if (count($month->days) > 0)
                                                            {
                                                                foreach ($month->days as $day)
                                                                {
                                                                    if ($day->day_number == $graphicRequest->day->day_number)
                                                                    {
                                                                        $employee['dayId'] = $day->id;
                                                                    }
                                                                }

                                                                if ($employee['dayId'] === null)
                                                                {
                                                                    $employee['monthId'] = $month->id;
                                                                }

                                                            } else {

                                                                $employee['monthId'] = $month->id;
                                                            }
                                                        }
                                                    }

                                                    if ($employee['monthId'] === null && $employee['dayId'] === null)
                                                    {
                                                        $employee['yearId'] = $year->id;
                                                    }

                                                } else {

                                                    $employee['yearId'] = $year->id;
                                                }
                                            }
                                        }
                                        
                                        if ($employee['yearId'] === null && $employee['monthId'] === null && $employee['dayId'] === null)
                                        {
                                            $employee['showProperty'] = true;
                                        }

                                    } else {

                                        $employee['showProperty'] = true;
                                    }
                                }

                            } else {

                                $employee['showProperty'] = true;
                            }
                        }
                    }
                }
            }
            
            $graphicRequest['allEmployees'] = $allEmployees;
            $graphicRequest['boss'] = User::where('id', $graphicRequest->property->boss_id)->first();
            
            $chosenMessage = Message::where('id', $chosenMessageId)->first();
            
            if ($chosenMessage !== null && $chosenMessage->owner_id !== auth()->user()->id)
            {
                $chosenMessage->status = 1;
                $chosenMessage->save();
            }
            
            $graphicRequestMessages = Message::where('graphic_request_id', $graphicRequest->id)->get();
            
            return view('admin.graphic_request')->with([
                'graphicRequest' => $graphicRequest,
                'graphicRequestMessages' => $graphicRequestMessages,
                'chosenMessage' => $chosenMessage !== null ? $chosenMessage : null
            ]);
        }
        
        return redirect()->route('welcome')->with('error', 'Podane zapytanie o grafik nie istnieję lub ma innego właściciela');
    }
    
    public function makeAMessage()
    {
        $rules = array(
            'text'               => 'required|string',
            'graphic_request_id' => 'required|numeric'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails())
        {
            return Redirect::to('admin/graphic-request/' . Input::get('graphic_request_id'));
            
        } else {
            
            $graphicRequest = GraphicRequest::where('id', Input::get('graphic_request_id'))->first();

            if ($graphicRequest !== null)
            {
                $message = new Message();
                $message->text = Input::get('text');
                $message->status = 0;
                $message->owner_id = auth()->user()->id;
                $message->graphic_request_id = $graphicRequest->id;
                $message->save();

                return redirect('/admin/graphic-request/' . $graphicRequest->id . '/' . $message->id)->with('success', 'Message has been sended!');
            }
            
            return redirect()->route('welcome')->with('error', 'Something went wrong');
        }
    }
    
    public function graphicRequestMessageChangeStatus($graphicRequestId, $messageId)
    {
        $graphicRequest = GraphicRequest::where('id', $graphicRequestId)->first();
        $message = Message::where('id', $messageId)->first();
        
        if ($graphicRequest !== null && $message !== null && $message->graphic_request_id == $graphicRequest->id)
        {
            if ($message->status == 0)
            {
                $message->status = 1;
                
            } else if ($message->status = 1) {
                
                $message->status = 0;
            }
            
            $message->save();
            
            return redirect('/admin/graphic-request/' . $graphicRequest->id . '/' . $message->id)->with('success', 'Message status has been changed!');
        }
        
        return redirect()->route('welcome')->with('error', 'Something went wrong');
    }
    
    public function approveMessages()
    {
        $promoCodes = PromoCode::where('id', '!=', null)->where('boss_id', '!=', null)->with('promo')->orderBy('activation_date', 'desc')->get();

        foreach ($promoCodes as $promoCode)
        {           
            $boss = User::where([
                'id' => $promoCode->boss_id,
                'isBoss' => 1
            ])->first();
            
            if ($boss !== null)
            {
                $promoCode['boss'] = $boss;
            }
        }
        
        return view('admin.approve_messages')->with([
            'promoCodes' => $promoCodes
        ]);
    }
    
    public function approveMessageShow($bossId)
    {
        $boss = User::where([
            'id' => $bossId,
            'isBoss' => 1
        ])->first();
        
        if ($boss !== null)
        {
            $promoCode = PromoCode::where('boss_id', $boss->id)->with([
                'messages',
                'promo'
            ])->first();

            if ($promoCode !== null)
            {
                $promoCode['boss'] = $boss;
                
                return view('admin.approve_message_show')->with([
                    'promoCode' => $promoCode,
                    'boss' => $boss,
                    'admin' => auth()->user()
                ]);
            }
        }
        
        return redirect()->route('welcome');
    }
    
    public function approveMessageStatusChange($promoCodeId)
    {
        $promoCode = PromoCode::where('id', $promoCodeId)->first();
 
        if ($promoCode !== null)
        {
            $boss = User::where([
                'id' => $promoCode->boss_id,
                'isBoss' => 1
            ])->first();
            
            if ($boss !== null)
            {
                if ($boss->isApproved == 0)
                {
                    $boss->isApproved = 1;
                    
                } else if ($boss->isApproved == 1) {
                    
                    $boss->isApproved = 0;
                }
                
                $boss->save();
            }
            
            return redirect()->action(
                'AdminController@approveMessageShow', [
                    'id' => $boss->id,
                ]
            )->with('success', 'User isApproved ahs been changed!');
        }
        
        return redirect()->route('welcome');
    }
    
    public function makeAnApproveMessage()
    {
        $rules = array(
            'text'          => 'required|string',
            'promo_code_id' => 'required|numeric',
            'boss_id'       => 'required|numeric'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails())
        {
            return Redirect::to('admin/approve/messages/' . Input::get('boss_id'));
            
        } else {
            
            $boss = User::where([
                'id' => Input::get('boss_id'),
                'isBoss' => 1
            ])->first();

            if ($boss !== null)
            {
                $promoCode = PromoCode::where([
                    'id' => Input::get('promo_code_id'),
                    'boss_id' => $boss->id
                ])->first();

                if ($promoCode !== null)
                {
                    $message = new Message();
                    $message->text = Input::get('text');
                    $message->status = 0;
                    $message->owner_id = auth()->user()->id;
                    $message->promo_code_id = $promoCode->id;
                    $message->save();
                    
                    return redirect('/admin/approve/messages/' . Input::get('boss_id'))->with('success', 'Message has been sended!');
                }
            }
            
            return redirect()->route('welcome')->with('error', 'Coś poszło nie tak');
        }
    }
    
    public function getUserImage($filename)
    {
        $file = Storage::disk('local')->get($filename);
        
        return new Response($file, 200);
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function promoCreate()
    {
        $subscriptions = Subscription::where('id', '!=', null)->get();
        
        return view('promo.create')->with('subscriptions', $subscriptions);
    }
    
    /**
     * Add promo.
     */
    public function promoStore()
    {
        $rules = array(
            'title'            => 'required',
            'title_en'         => 'required',
            'description'      => 'required',
            'description_en'   => 'required',
            'total_code_count' => 'required',
            'code'             => 'required',
            'subscriptions'    => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return Redirect::to('admin/promo/create')
                ->withErrors($validator);
        } else {

            $admin = User::where([
                'id' => auth()->user()->id,
                'isAdmin' => 1
            ])->first();

            if ($admin !== null)
            {
                $promo = new Promo();
                $promo->title = Input::get('title');
                $promo->title_en = Input::get('title_en');
                $promo->description = Input::get('description');
                $promo->description_en = Input::get('description_en');
                $promo->available_code_count = Input::get('total_code_count');
                $promo->total_code_count = Input::get('total_code_count');
                $promo->admin_id = $admin->id;
                $promo->save();

                if ($promo !== null)
                {
                    for ($i = 1; $i <= $promo->total_code_count; $i++)
                    {
                        $promoCode = new PromoCode();
                        $promoCode->code = Input::get('code');
                        $promoCode->promo_id = $promo->id;
                        $promoCode->save();

                        foreach (Input::get('subscriptions') as $sub)
                        {
                            $subscription = Subscription::where('id', $sub)->first();

                            if ($subscription !== null)
                            {
                                $promoCode->subscriptions()->attach($subscription->id);
                            }
                        }
                    }
                }
                
                return redirect()->action(
                    'AdminController@promoShow', [
                        'id' => $promo->id,
                    ]
                )->with('success', 'Promo with promo codes have been created!');
            }
            
            return redirect()->route('welcome');
        }
    }
    
    public function promoShow($id)
    {
        $promo = Promo::where('id', $id)->with('promoCodes')->first();
        
        if ($promo !== null)
        {
            if (count($promo->promoCodes) > 0)
            {
                foreach ($promo->promoCodes as $promoCode)
                {
                    if ($promoCode->isActive == 1)
                    {
                        $boss = User::where('id', $promoCode->boss_id)->first();
                        
                        if ($boss !== null)
                        {
                            $promoCode['boss'] = $boss;
                        }
                    }
                }
            }
            
            return view('promo.show')->with([
                'promo' => $promo
            ]);
        }
        
        return redirect()->route('welcome');
    }
    
    public function promoEdit($id)
    {
        $promo = Promo::where('id', $id)->first();
        
        if ($promo !== null)
        {            
            return view('promo.edit')->with([
                'promo' => $promo
            ]);
        }
        
        return redirect()->route('welcome');
    }
    
    public function promoUpdate()
    {
        $rules = array(
            'promo_id'         => 'required',
            'title'            => 'required',
            'title_en'         => 'required',
            'description'      => 'required',
            'description_en'   => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return Redirect::to('admin/promo/edit/' . Input::get('promo_id'))
                ->withErrors($validator);
        } else {
            
            $admin = User::where([
                'id' => auth()->user()->id,
                'isAdmin' => 1
            ])->first();

            if ($admin !== null)
            {
                $promo = Promo::where('id', Input::get('promo_id'))->first();
                
                if ($promo !== null)
                {
                    $promo->title = Input::get('title');
                    $promo->title_en = Input::get('title_en');
                    $promo->description = Input::get('description');
                    $promo->description_en = Input::get('description_en');                    
                    $promo->save();
                    
                    return redirect()->action(
                        'AdminController@promoShow', [
                            'id' => $promo->id,
                        ]
                    )->with('success', 'Promo has been updated!');
                }
            }
            
            return redirect()->route('welcome');
        }
    }
    
    public function promoList()
    {
        $promos = Promo::where('id', '!=', null)->get();
        
        return view('promo.list')->with('promos', $promos);
    }
    
    public function promoCodeShow($id)
    {
        $promoCode = PromoCode::where('id', $id)->with([
            'boss',
            'promo',
            'subscriptions',
            'messages'
        ])->first();
        
        if ($promoCode !== null)
        {
            return view('promo.code_show')->with([
                'promoCode' => $promoCode
            ]);
        }
        
        return redirect()->route('welcome');
    }
    
    public function makeAPromoCodeMessage()
    {
        $rules = array(
            'text'          => 'required|string',
            'promo_code_id' => 'required|numeric'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails())
        {
            return Redirect::to('admin/promo-code/show/' . Input::get('promo_code_id'));
            
        } else {
            
            $promoCode = PromoCode::where('id', Input::get('promo_code_id'))->first();

            if ($promoCode !== null)
            {
                $message = new Message();
                $message->text = Input::get('text');
                $message->status = 0;
                $message->owner_id = auth()->user()->id;
                $message->promo_code_id = $promoCode->id;
                $message->save();

                return redirect('/admin/promo-code/show/' . $promoCode->id)->with('success', 'Message has been sended!');
            }
            
            return redirect()->route('welcome')->with('error', 'Something went wrong');
        }
    }
    
    public function promoCodeMessageChangeStatus($promoId, $messageId)
    {
        $promoCode = PromoCode::where('id', $promoId)->first();
        $message = Message::where('id', $messageId)->first();
        
        if ($promoCode !== null && $message !== null && $message->promo_code_id == $promoCode->id)
        {
            if ($message->status == 0)
            {
                $message->status = 1;
                
            } else if ($message->status = 1) {
                
                $message->status = 0;
            }
            
            $message->save();
            
            return redirect('/admin/promo-code/show/' . $promoCode->id)->with('success', 'Message status has been changed!');
        }
        
        return redirect()->route('welcome')->with('error', 'Something went wrong');
    }
    
    public function promoActivationToggle($promoId)
    {
        $promo = Promo::where('id', $promoId)->first();
        
        if ($promo !== null)
        {
            if ($promo->isActive == 0)
            {
                $promo->isActive = 1;
                
            } else if ($promo->isActive = 1) {
                
                $promo->isActive = 0;
            }
            
            $promo->save();
            
            return redirect('/admin/promo/show/' . $promo->id)->with('success', 'Promo activation has been changed!');
        }
        
        return redirect()->route('welcome')->with('error', 'Something went wrong');
    }
}
