<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Redirect;

class BossController extends Controller
{    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('boss');
    }
    
    /**
     * Shows boss dashboard
     */
    public function dashboard()
    {
        $code = auth()->user()->code;
        
        return view('boss.dashboard')->with([
            'code' => $code
        ]);
    }
    
    /**
     * Sets registration code for workers
     * 
     * @param Request $request
     * @return type
     */
    public function setCode(Request $request)
    {
        // validate
        $rules = array(
            'code' => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        // process the login
        if ($validator->fails()) {
            return Redirect::to('boss/dashboard')
                ->withErrors($validator)
                ->withInput(Input::except('password'));
        } else {
            // store
            $boss = auth()->user();
            $boss->code = Input::get('code');
            $boss->save();

            // redirect
            return redirect('/boss/dashboard')->with('success', 'Kod rejestracyjny dla pracowników został zapisany!');
        }
    }
}
