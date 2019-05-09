<?php

namespace App\Http\Controllers;

use App\Calendar;
use App\Year;
use App\Month;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Redirect;

class YearController extends Controller
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
        $calendar = Calendar::where('id', $id)->first();
        
        return view('year.create')->with('calendar', $calendar);
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
            'year' => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        // process the login
        if ($validator->fails()) {
            return Redirect::to('year/create')
                ->withErrors($validator);
        } else {
            // store            
            $year = Year::firstOrCreate([
                'year' => Input::get('year'),
                'calendar_id' => Input::get('calendar_id')
            ]);
            
            return redirect()->action(
                        'YearController@show', [
                            'id' => $year->id
                    ])->with('success', 'Year successfully created!')
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
        $year = Year::where('id', $id)->first();
        $months = Month::where('year_id', $year->id)->orderBy('month_number')->get();
        
        if ($year)
        {
            $calendar = Calendar::where('id', $year->calendar_id)->first();
        }
        
        return view('year.show')->with([
            'year' => $year,
            'months' => $months,
            'property_id' => $calendar->property_id
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $year = Year::where('id', $id)->first();
        
        $calendar = Calendar::where('id', $year->calendar_id)->first();
        
        $year->delete();
        
        return redirect()->action(
                    'PropertyController@show', [
                        'id' => $calendar->property_id
                ])->with('success', 'Year has been successfully deleted!')
        ;
    }
}
