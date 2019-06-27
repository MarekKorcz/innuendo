<?php

namespace App\Http\Controllers;

use App\User;
use App\TempUser;
use App\TempProperty;
use App\Mail\AdminTempBossCreate;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
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
        // todo: populate DB with real data and check if they are being displayed properly
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

        return view('admin.employee_list')->with([
            'employees' => $employees
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
            
            // wyświetl wszystkie wykupione subskrypcje (z podziałem na prywatne i publiczne) + zrób button do wyświetlania tych wizyt
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
            
            // zrób tak żeby były wyświetlone dane user'a z buttonami do zmiany jego danych
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
     * Display specific resource.
     *
     * @param integer $id
     * @return Response
     */
    public function employeeShow($id)
    {
        if ($id !== null)
        {            
            $employee = User::where([
                'id' => $id,
                'isEmployee' => 1
            ])->with('calendars')->first();
            
            dump($employee);
            dump($employee->calendars);
            die;

            // todo: create this view
//            return view('admin.employee_show')->with([
//                'employee' => $employee
//            ]);
        }
        
        return redirect()->route('welcome');
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
    public function bossStore(Request $request)
    {
        $rules = array(
            'name'           => 'required',
            'surname'        => 'required',
            'boss_email'     => 'required',
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
}
