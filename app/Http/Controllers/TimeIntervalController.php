<?php

namespace App\Http\Controllers;

use App\Day;
use App\TimeInterval;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Redirect;

class TimeIntervalController extends Controller
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
        $day = Day::find($id);
        
        return view('time_interval.create')->with('day', $day);
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
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time'
        );
        $validator = Validator::make(Input::all(), $rules);

        // process the login
        if ($validator->fails()) {
            return Redirect::to('interval/create')
                ->withInput(Input::except('password'))
                ->withErrors($validator);
        } else {
            
            // count total time
            $startDate = \DateTime::createFromFormat('H:i', Input::get('start_time'));
            $endDate = \DateTime::createFromFormat('H:i', Input::get('end_time'));
            
            $interval = $startDate->diff($endDate);
            $minutes = 0;
            
            if ($interval->h > 0)
            {
                $minutes = $interval->h * 60;
            }
            
            $minutes += $interval->i;
            
            // store            
            $timeInterval = TimeInterval::firstOrCreate([
                'start_time' => Input::get('start_time'),
                'end_time' => Input::get('end_time'),
                'total_time' => $minutes,
                'day_id' => Input::get('day_id')
            ]);
            
            return redirect()
                    ->action('DayController@show', ['id' => Input::get('day_id')])
                    ->with('success', 'Time interval has been successfully added!')
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
