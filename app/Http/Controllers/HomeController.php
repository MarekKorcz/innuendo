<?php

namespace App\Http\Controllers;

//// to tests
//use App\Appointment;
//use App\Interval;
//use App\Substart;
//use App\Purchase;
//use App\Day;
//use App\Month;
//use App\Year;
//use Illuminate\Support\Collection;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = auth()->user();
        
        if ($user->isAdmin !== null)
        {
            $route = 'home_admin';
            
        } else if ($user->isEmployee !== null) {
            
            $route = 'home_employee';
            
        } else if ($user->isBoss !== null) {
            
            $route = 'home_boss';
            
        } else {
            
            $route = 'home';
        }
        
        return view($route)->with([
            'user' => $user
        ]);
    }
    
//    public function test()
//    {
//    }
}
