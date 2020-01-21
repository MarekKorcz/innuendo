<?php

namespace App\Http\Controllers;

use App\Day;
use App\Graphic;
use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Redirect;

class GraphicController extends Controller
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
        $day = Day::where('id', $id)->first();
        
        return view('graphic.create')->with([
            'day' => $day,
            'employees' => User::where('isEmployee', 1)->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $rules = array(
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'employee_id' => 'required',
            'day_id' => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return Redirect::to('graphic/' . Input::get('day_id'))
                ->withErrors($validator);
        } else {
            
            $day = Day::where('id', Input::get('day_id'))->with('month.year.property')->first();
            
            // >> get given employee
            $givenEmployee = User::where([
                'id' => Input::get('employee_id'),
                'isEmployee' => 1
            ])->first();
            // <<
            
            if ($day !== null && $givenEmployee !== null)
            {
                // count total time
                $startDate = \DateTime::createFromFormat('H:i', Input::get('start_time'));
                $endDate = \DateTime::createFromFormat('H:i', Input::get('end_time'));

                $graphic = $startDate->diff($endDate);
                $minutes = 0;

                if ($graphic->h > 0)
                {
                    $minutes = $graphic->h * 60;
                }

                $minutes += $graphic->i;
                
                $graphicTime = new Graphic();
                $graphicTime->start_time = Input::get('start_time');
                $graphicTime->end_time = Input::get('end_time');
                $graphicTime->total_time = $minutes;
                $graphicTime->day_id = $day->id;
                $graphicTime->property_id = $day->month->year->property->id;
                $graphicTime->employee_id = $givenEmployee->id;
                $graphicTime->save();

                return redirect()->action('DayController@show', [
                        'id' => $day->id
                ])->with('success', 'Graphic has been successfully added!');
            }
        }
    }
    
    public function edit($id)
    {
        $graphic = Graphic::where('id', $id)->with([
            'day',
            'employee'
        ])->first();
        
        if ($graphic !== null)
        {
            return view('graphic.edit')->with([
                'graphic' => $graphic,
                'day' => $graphic->day,
                'employees' => User::where('isEmployee', 1)->get()
            ]);
        }
        
        return redirect()->route('welcome');
    }
    
    public function update()
    {
        $rules = array(
            'start_time'  => 'required',
            'end_time'    => 'required',
            'graphic_id'  => 'required',
            'employee_id' => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return Redirect::to('/graphic/' . Input::get('graphic_id') . '/edit')
                ->withErrors($validator);
        } else {
        
            $graphic = Graphic::where('id', Input::get('graphic_id'))->with([
                'appointments',
                'day',
                'employee'
            ])->first();
        
            // >> get given employee
            $givenEmployee = User::where([
                'id' => Input::get('employee_id'),
                'isEmployee' => 1
            ])->first();
            // <<
            
            if ($graphic !== null && $givenEmployee !== null)
            {
                // >> count total time
                $startTimeExploded = explode(":", Input::get('start_time'));
                $endTimeExploded = explode(":", Input::get('end_time'));
                
                $startDate = \DateTime::createFromFormat('H:i', $startTimeExploded[0] . ":" . $startTimeExploded[1]);
                $endDate = \DateTime::createFromFormat('H:i', $endTimeExploded[0] . ":" . $endTimeExploded[1]);                
                
                $graph = $startDate->diff($endDate);
                $minutes = 0;

                if ($graph->h > 0)
                {
                    $minutes = $graph->h * 60;
                }

                $minutes += $graph->i;
                // <<
                
                $graphic->start_time = Input::get('start_time');
                $graphic->end_time = Input::get('end_time');
                $graphic->total_time = $minutes;
                if ($givenEmployee->id !== $graphic->employee->id)
                {
                    $graphic->employee_id = $givenEmployee->id;
                }
                $graphic->save();
                
                return redirect()->action('DayController@show', [
                    'id' => $graphic->day->id
                ])->with('success', 'Graphic has been successfully updated!');
            }

            return redirect('/graphic/' . Input::get('graphic_id') . '/edit')->with('error', 'Something went wrong!');
        }
    }
}
