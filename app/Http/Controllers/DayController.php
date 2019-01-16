<?php

namespace App\Http\Controllers;

use App\Year;
use App\Month;
use App\Day;
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
        $month = Month::find($id);
        
        return view('day.create')->with('month', $month);
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
            'start_day' => 'required|numeric',
            'end_day' => 'required|numeric|gte:start_day'
        );
        $validator = Validator::make(Input::all(), $rules);

        // process the login
        if ($validator->fails()) {
            return Redirect::to('day/create')
                ->withInput(Input::except('password'))
                ->withErrors($validator);
        } else {
            
            $month = Month::find(Input::get('month_id'));
            $year = Year::find($month->year_id);
            
            $monthNumber = strlen($month->month_number) == 2 ? $month->month_number : "0" . $month->month_number;
            $yearNumber = $year->year;
            
            $yearMonth = $yearNumber . "-" . $monthNumber;
            
            $currentMonth = new \DateTime($yearMonth . "-01");
            $numberInMonth = $currentMonth->format("t");
            
            for ($i = Input::get('start_day'); $i <= Input::get('end_day'); $i++)
            {
                if ($i > 0 && $i <= $numberInMonth)
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

            return redirect()
                    ->action('MonthController@show', ['id' => $month->id])
                    ->with('success', 'Days have been successfully created!')
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
