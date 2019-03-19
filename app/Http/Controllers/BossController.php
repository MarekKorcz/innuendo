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
        $code = $request->request->get('code');
        
        if (is_string($code))
        {
            if ($code == "true")
            {
                $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $charactersLength = strlen($characters);
                $code = "";
                
                for ($i = 0; $i < 12; $i++) 
                {
                    $code .= $characters[rand(0, $charactersLength - 1)];
                }
                
                $message = 'Rejestracja pracowników została WŁĄCZONA';
                
            } else if ($code = "false") {
                
                $code = null;
                $message = 'Rejestracja pracowników została WYŁĄCZONA';
            }
            
            // store
            $boss = auth()->user();
            $boss->code = $code;
            $boss->save();
        }

        // redirect
        return redirect('/boss/dashboard')->with('success', $message);
    }
}
