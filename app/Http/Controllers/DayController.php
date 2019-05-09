<?php

namespace App\Http\Controllers;

use App\Year;
use App\Month;
use App\Day;
use App\Graphic;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Redirect;

class DayController extends Controller
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
        $month = Month::where('id', $id)->first();
        
        return view('day.create')->with('month', $month);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $rules = array(
            'start_day' => 'required|numeric',
            'end_day' => 'required|numeric|gte:start_day'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return Redirect::to('day/create')
                ->withErrors($validator);
        } else {
            
            $month = Month::where('id', Input::get('month_id'))->first();
            $year = Year::where('id', $month->year_id)->first();
            
            $monthNumber = strlen($month->month_number) == 2 ? $month->month_number : "0" . $month->month_number;
            $yearNumber = $year->year;
            
            $yearMonth = $yearNumber . "-" . $monthNumber;
            
            for ($i = Input::get('start_day'); $i <= Input::get('end_day'); $i++)
            {
                if ($i > 0 && $i <= $month->days_in_month)
                {
                    $dayNumber = strlen($i) == 2 ? $i : "0" . $i;
                    
                    $fullDate = $yearMonth . "-" . $dayNumber;
                    $dayDate = new \DateTime($fullDate);
                    
                    if ($dayDate->format("N") != 7)
                    {
                        $day = Day::firstOrCreate([
                            'day_number' => $dayDate->format("j"),
                            'number_in_week' => $dayDate->format("N"),
                            'month_id' => Input::get('month_id')
                        ]);
                    }
                }
            }

            return redirect()->action(
                        'MonthController@show', [
                            'id' => $month->id
                        ]
                    )->with('success', 'Days have been successfully created!')
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
        $day = Day::where('id', $id)->first();
        $graphicTime = Graphic::where('day_id', $day->id)->first();
        
        $graphic = [];
        
        if ($graphicTime !== null)
        {
            $workUnits = ($graphicTime->total_time / 60) * 2;
            $startTime = date('G:i', strtotime($graphicTime->start_time));
            
            $startTimePart = explode(":", $startTime);
            $startTime = $startTimePart[0] . ":" . $startTimePart[1];
            
            for ($i = 0; $i < $workUnits; $i++) 
            {
                $graphic[] = [
                    $startTime,
                    'place to show asigned employee'
                ];
                
                $timeIncrementedBy30Minutes = strtotime("+30 minutes", strtotime($startTime));
                $startTime = date('G:i', $timeIncrementedBy30Minutes);
            }
        }
        
        if ($day)
        {
            $month = Month::where('id', $day->month_id)->first();
        }
        
        return view('day.show')->with([
            'day' => $day,
            'graphic' => $graphic,
            'month' => $month
        ]);
    }
}
