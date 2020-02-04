<?php

namespace App\Http\Controllers;

use App\User;
use App\TempUser;
use App\TempProperty;
use App\GraphicRequest;
use App\Message;
use App\Subscription;
use App\Promo;
use App\PromoCode;
use App\InvoiceData;
use App\Mail\AdminTempBossCreate;
use App\Mail\AdminTempEmployeeCreate;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;
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
     * @return Response
     */
    public function userList()
    {
        $users = User::where([
            'isAdmin' => null,
            'isBoss' => null,
            'isEmployee' => null
        ])->orderBy('created_at', 'desc')->get();
        
        if (count($users) > 0)
        {
            foreach ($users as $user)
            {
                $user['boss'] = $user->getBoss();
            }
        }

        return view('admin.user_list')->with([
            'users' => $users
        ]);
    }
    
    /**
     * @param integer $id
     * @return Response
     */
    public function userShow($id)
    {
        $user = User::where([
            'id' => $id,
            'isBoss' => null,
            'isEmployee' => null,
            'isAdmin' => null
        ])->first();
        
        if ($user !== null)
        {            
            return view('admin.user_show')->with([
                'user' => $user,
                'userBoss' => $user->getBoss(),
                'bosses' => User::where('isBoss', '!=', null)->get()
            ]);
        }
        
        return redirect()->route('welcome');
    }
    
    /**
     * @return Response
     */
    public function userUpdate()
    {
        $rules = array(
            'name'           => 'required',
            'surname'        => 'required',
            'email'           => 'required',
            'phone_number'   => 'required',
            'isBoss'          => 'required',
            'boss_id'          => 'required',
            'user_id'          => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return Redirect::to('admin/user/show/' . Input::get('user_id'))
                ->withErrors($validator);
        } else {
            
            $user = User::where([
                'id' => Input::get('user_id')
            ])->first();
            
            if ($user !== null)
            {
                $user->name = Input::get('name');
                $user->surname = Input::get('surname');
                $user->email = Input::get('email');
                $user->phone_number = Input::get('phone_number');
                
                if (Input::get('isBoss'))
                {
                    $user->isBoss = 1;
                    $user->boss_id = null;
                    
                } else {
                    
                    $boss = User::where([
                        'id' => Input::get('boss_id'),
                        'isBoss' => 1
                    ])->first();
                    
                    if ($boss !== null)
                    {
                        $user->isBoss = null;
                        $user->boss_id = $boss->id;
                    }
                }
                
                $user->save();
                
                if ($user->isBoss)
                {
                    return redirect('admin/boss/show/' . $user->id)->with('success', 'Entity has been successfully updated!');
                    
                } else {
                    
                    return redirect('admin/user/show/' . $user->id)->with('success', 'Entity has been successfully updated!');
                }
            }
        }
    }
    
    /**
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
     * @param integer $id
     * @return Response
     */
    public function bossShow($id)
    {
        $boss = User::where([
            'id' => $id,
            'isBoss' => 1
        ])->with([
            'promoCode',
            'properties'
        ])->first();

        return view('admin.boss_show')->with([
            'boss' => $boss,
            'properties' => $boss->properties,
            'workers' => $boss->getWorkers()
        ]);
        
        return redirect()->route('welcome');
    }
    
    public function getPotentiallyNewBosses(Request $request)
    {
        if ($request->get('bossId'))
        {
            $boss = User::where([
                'id' => $request->get('bossId'),
                'isBoss' => 1
            ])->first();
            
            if ($boss !== null)
            {
                $newBossesArr = [];
                $potentiallyNewBosses = $boss->getWorkers();

                if (count($potentiallyNewBosses) > 0)
                {
                    foreach ($potentiallyNewBosses as $boss)
                    {
                        $newBossesArr[] = [
                            'id' => $boss->id,
                            'name' => $boss->name . ' ' . $boss->surname
                        ];
                    }
                }

                return new JsonResponse([
                    'type' => 'success',
                    'bosses' => $newBossesArr,
                    'label_description' => \Lang::get('common.choose_new_boss') . ':'
                ], 200, array(), true);
            }
        }
        
        return new JsonResponse(array(
            'type'    => 'error'        
        ));
    }
    
    /**
     * @return Response
     */
    public function bossUpdate()
    {
        $rules = array(
            'name'         => 'required',
            'surname'      => 'required',
            'email'        => 'required',
            'phone_number' => 'required',
            'is_boss'      => 'required',
            'boss_id'      => 'required',
            'new_boss'     => 'sometimes|required'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return Redirect::to('admin/user/show/' . Input::get('boss_id'))
                ->withErrors($validator);
        } else {
            
            $boss = User::where([
                'id' => Input::get('boss_id')
            ])->with('properties')->first();
            
            if ($boss !== null)
            {
                $boss->name = Input::get('name');
                $boss->surname = Input::get('surname');
                $boss->email = Input::get('email');
                $boss->phone_number = Input::get('phone_number');
                
                // if turn 'boss' off, get 'new boss', asign its id to 'boss workers' and 'boss properties'
                // plus change 'boss' into 'new boss' worker
                if (Input::get('isBoss') == false && Input::get('new_boss') !== null) {
                    
                    // getting 'new boss' from 'boss workers'
                    $newBoss = User::where([
                        'id' => Input::get('new_boss'),
                        'boss_id' => $boss->id
                    ])->first();
                    
                    if ($newBoss !== null)
                    {
                        $bossWorkers = $boss->getWorkers();                        
                        
                        if (count($bossWorkers) > 0)
                        {
                            foreach ($bossWorkers as $worker)
                            {
                                if ($worker->id !== $newBoss->id)
                                {
                                    $worker->boss_id = $newBoss->id;
                                }
                            }
                        }
                        
                        $bossProperties = $boss->properties;
                        
                        if (count($bossProperties) > 0)
                        {
                            foreach ($bossProperties as $property)
                            {
                                $property->boss_id = $newBoss->id;
                                $property->save();
                            }
                        }
                        
                        $newBoss->isBoss = 1;
                        $newBoss->boss_id = null;
                        $newBoss->save();

                        $boss->isBoss = null;
                        $boss->boss_id = $newBoss->id;
                    }
                }
                
                $boss->save();
                
                if ($boss->isBoss)
                {
                    return redirect('admin/boss/show/' . $boss->id)->with('success', 'Entity has been successfully updated!');
                    
                } else {
                    
                    return redirect('admin/user/show/' . $boss->id)->with('success', 'Entity has been successfully updated!');
                }
            }
        }
    }
    
    /**
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
     * @param string $slug
     * @return Response
     */
    public function employeeShow($slug)
    {
        $employee = User::where([
            'isEmployee' => 1,
            'slug' => $slug
        ])->with('graphics.property')->first();
        
        if ($employee !== null)
        {
            return view('admin.employee_show')->with([
                'employee' => $employee
            ]);
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
            'phone_number'   => 'required',
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
            $employee->phone_number = Input::get('phone_number');
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
     * Updates employee data.
     *
     * @return Response
     */
    public function employeeUpdate()
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
            'day.month.year',
            'employees'
        ])->get();

        foreach ($graphicRequests as $graphicRequest)
        {                   
            $graphicRequest['boss'] = User::where('id', $graphicRequest->property->boss_id)->first();
        }
        
        return view('admin.graphic_requests')->with([
            'graphicRequests' => $graphicRequests
        ]);
    }
    
    public function graphicRequestShow($graphicRequestId)
    {
        $graphicRequest = GraphicRequest::where('id', $graphicRequestId)->with([
            'property.boss',
            'day.month.year',
            'employees',
            'messages.user'
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
                        if (!$employee['isChosen'])
                        {
                            $employee['isChosen'] = $employee->id == $chosenEmployee->id ? true : false;
                        }
                    }
                }
            }
            
            $graphicRequest['allEmployees'] = $allEmployees;
            $graphicRequest['boss'] = $graphicRequest->property->boss;
            $graphicRequest['property'] = $graphicRequest->day->month->year->property;
            $graphicRequest['year'] = $graphicRequest->day->month->year;
            $graphicRequest['month'] = $graphicRequest->day->month;
                        
            return view('admin.graphic_request')->with([
                'graphicRequest' => $graphicRequest,
                'graphicRequestMessages' => $graphicRequest->messages
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
                $message->user_id = auth()->user()->id;
                $message->graphic_request_id = $graphicRequest->id;
                $message->save();

                return redirect('/admin/graphic-request/' . $graphicRequest->id)->with('success', 'Message has been sended!');
            }
            
            return redirect()->route('welcome')->with('error', 'Something went wrong');
        }
    }
    
//    public function userMessages()
//    {
//        
//        
//        
//        
//        $contactMessages = Message::where([
//            'graphic_request_id' => null,
//            'promo_code_id' => null
//        ])->get();
//        
//        
//        
//        
//        $promoCodes = PromoCode::where([
//            ['id', '!=', null],
//            ['boss_id', '!=', null],
//            ['is_active', '!=', 0]
//        ])->with([
//            'promo',
//            'boss'
//        ])
//        ->orderBy('activation_date', 'desc')
//        ->get();
//        
//        
//        
//        
//        $graphicRequests = GraphicRequest::where('id', '!=', null)->with([
//            'property',
//            'day.month.year',
//            'employees'
//        ])->get();
//
//        foreach ($graphicRequests as $graphicRequest)
//        {                   
//            $graphicRequest['boss'] = User::where('id', $graphicRequest->property->boss_id)->first();
//        }
//        
//        
//        
//        
//        
//        
//        dd($contactMessages, $promoCodes, $graphicRequests);
//        
//        return view('admin.contact_messages')->with([
//            'contactMessages' => $contactMessages
//        ]);
//        
//        
//        
//        
//    }
    
    public function approveMessages()
    {
        $promoCodes = PromoCode::where([
            ['id', '!=', null],
            ['boss_id', '!=', null],
            ['is_active', '!=', 0]
        ])->with([
            'promo',
            'boss'
        ])
        ->orderBy('activation_date', 'desc')
        ->get();
        
        return view('admin.approve_messages')->with([
            'promoCodes' => $promoCodes
        ]);
    }
    
    public function approveMessageShow($bossId, $promoId)
    {
        $promoCode = PromoCode::where([
            'boss_id' => $bossId,
            'promo_id' => $promoId,
        ])->with([
            'messages',
            'promo',
            'boss'
        ])->first();

        if ($promoCode !== null)
        {
            return view('admin.approve_message_show')->with([
                'promoCode' => $promoCode,
                'admin' => auth()->user()
            ]);
        }
        
        return redirect()->route('welcome');
    }
    
    public function contactMessages()
    {
        $contactMessages = Message::where([
            'graphic_request_id' => null,
            'promo_code_id' => null
        ])->get();
        
        return view('admin.contact_messages')->with([
            'contactMessages' => $contactMessages
        ]);
    }
    
    public function approveMessageStatusChange($promoCodeId)
    {
        $promoCode = PromoCode::where('id', $promoCodeId)->with('boss')->first();
        
        if ($promoCode !== null && $promoCode->boss !== null)
        {
            if ($promoCode->boss->is_approved == 0)
            {
                $promoCode->boss->is_approved = 1;

            } else if ($promoCode->boss->is_approved == 1) {

                $promoCode->boss->is_approved = 0;
            }

            $promoCode->boss->save();

            return redirect()->action(
                'AdminController@approveMessageShow', [
                    'bossId' => $promoCode->boss->id,
                    'promoId' => $promoCode->promo->id
                ]
            )->with('success', 'User is_approved has been changed!');
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
            
            $promoCode = PromoCode::where([
                'id' => Input::get('promo_code_id'),
                'boss_id' => Input::get('boss_id')
            ])->with([
                'promo',
                'boss'
            ])->first();

            if ($promoCode !== null)
            {
                $message = new Message();
                $message->text = Input::get('text');
                $message->status = 0;
                $message->user_id = auth()->user()->id;
                $message->promo_code_id = $promoCode->id;
                $message->save();

                return redirect('/admin/approve/messages/' . $promoCode->boss->id . '/' . $promoCode->promo->id)->with('success', 'Message has been sended!');
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
        
        return view('promo.create')->with('subscriptions', $subscriptions->sortBy('quantity'));
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
                $promo->isActive = 1;
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
        $code = '';
        
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
                
                $code = $promo->promoCodes->first()->code;
            }
            
            return view('promo.show')->with([
                'promo' => $promo,
                'code' => $code
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
