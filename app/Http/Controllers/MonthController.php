<?php

namespace App\Http\Controllers;

use App\Year;
use App\Month;
use App\Day;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Redirect;

class MonthController extends Controller
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
     * Show the form for creating a new resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function create($id)
    {
        $year = Year::find($id);
        
        return view('month.create')->with('year', $year);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        // validate
        $rules = array(
            'month' => 'required',
            'month_number' => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        // process the login
        if ($validator->fails()) {
            return Redirect::to('month/create')
                ->withInput(Input::except('password'))
                ->withErrors($validator);
        } else {
            // store            
            $month = Month::firstOrCreate([
                'month' => Input::get('month'),
                'month_number' => Input::get('month_number'),
                'year_id' => Input::get('year_id')
            ]);
            
            return redirect()
                    ->action('MonthController@show', ['id' => $month->id])
                    ->with('success', 'Month successfully created!')
            ;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {        
        $month = Month::find($id);
        $days = Day::where('month_id', $month->id)->get();
        
        if ($month)
        {
            $year = Year::find($month->year_id);
        }
        
        return view('month.show')->with('month', $month)->with('days', $days)->with('year', $year);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $month = Month::find($id);
        $year = Year::find($month->year_id);
        
        $month->delete();
        
        return redirect()
                ->action('YearController@show', ['id' => $year->id])
                ->with('success', 'Month has been successfully deleted!')
        ;
    }
}
