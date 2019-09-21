<?php

namespace App\Http\Controllers;

use App\Day;
use App\Graphic;
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
        
        return view('graphic.create')->with('day', $day);
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
            'end_time' => 'required|date_format:H:i|after:start_time'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return Redirect::to('graphic/' . Input::get('day_id'))
                ->withErrors($validator);
        } else {
            
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
            
            $graphicTime = Graphic::firstOrCreate([
                'start_time' => Input::get('start_time'),
                'end_time' => Input::get('end_time'),
                'total_time' => $minutes,
                'day_id' => Input::get('day_id')
            ]);
            
            return redirect()->action('DayController@show', [
                    'id' => Input::get('day_id')
            ])->with('success', 'Graphic has been successfully added!');
        }
    }
    
    public function edit($id)
    {
        $graphic = Graphic::where('id', $id)->with('day')->first();
        
        if ($graphic !== null)
        {
            return view('graphic.edit')->with([
                'graphic' => $graphic,
                'day' => $graphic->day
            ]);
        }
        
        return redirect()->route('welcome');
    }
    
    public function update()
    {
        $rules = array(
            'start_time'  => 'required',
            'end_time'    => 'required',
            'graphic_id'  => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return Redirect::to('/graphic/' . Input::get('graphic_id') . '/edit')
                ->withErrors($validator);
        } else {
        
            $graphic = Graphic::where('id', Input::get('graphic_id'))->with([
                'appointments',
                'day'
            ])->first();
        
            if ($graphic !== null)
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
                $graphic->save();
                
                return redirect()->action('DayController@show', [
                    'id' => $graphic->day->id
                ])->with('success', 'Graphic has been successfully updated!');
            }

            return redirect('/graphic/' . Input::get('graphic_id') . '/edit')->with('error', 'Something went wrong!');
        }
    }
}
