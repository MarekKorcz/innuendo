<?php

namespace App\Http\Controllers;

use App\Purchase;

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
        $purchasedSubscriptions = Purchase::where('user_id', $user->id)->get();
        
        return view('home')->with([
            'user' => $user,
            'purchasedSubscriptions' => $purchasedSubscriptions
        ]);
    }
}
