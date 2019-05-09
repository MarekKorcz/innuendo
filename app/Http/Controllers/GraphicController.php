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
            return Redirect::to('graphic/create')
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
            
            return redirect()->action(
                        'DayController@show', [
                            'id' => Input::get('day_id')
                    ])->with('success', 'Graphic has been successfully added!')
            ;
        }
    }
}
