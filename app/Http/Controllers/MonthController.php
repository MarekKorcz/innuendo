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
        $year = Year::where('id', $id)->first();
        
        return view('month.create')->with('year', $year);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $rules = array(
            'month' => 'required',
            'month_number' => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return Redirect::to('month/create')
                ->withErrors($validator);
        } else {
            
            $year = Year::where('id', Input::get('year_id'))->first();
            
            $monthNumber = strlen(Input::get('month_number')) == 2 ? Input::get('month_number') : "0" . Input::get('month_number');
            $yearNumber = $year->year;
            
            $yearMonth = $yearNumber . "-" . $monthNumber;
            
            $currentMonth = new \DateTime($yearMonth . "-01");
            $numberInMonth = $currentMonth->format("t");
                 
            $month = Month::firstOrCreate([
                'month' => Input::get('month'),
                'month_number' => Input::get('month_number'),
                'days_in_month' => $numberInMonth,
                'year_id' => Input::get('year_id')
            ]);
            
            return redirect()->action(
                        'MonthController@show', [
                            'id' => $month->id
                    ])->with('success', 'Month successfully created!')
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
        $month = Month::where('id', $id)->first();
        $days = Day::where('month_id', $month->id)->orderBy('day_number')->get();
        
        if ($month)
        {
            $year = Year::where('id', $month->year_id)->first();
        }
        
        $days = $this->formatDaysToCalendarForm($days, $month->days_in_month);
        
        return view('month.show')->with([
            'month' => $month,
            'days' => $days,
            'year' => $year
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
        $month = Month::where('id', $id)->first();
        
        if ($month !== null)
        {
            $year = Year::where('id', $month->year_id)->first();
            
            $month->delete();

            return redirect()->action(
                        'YearController@show', [
                            'id' => $year->id
                    ])->with('success', 'Month has been successfully deleted!')
            ;
        }
        
        return redirect()->route('welcome')->with('error', 'Such month doesn\'t exist');
    }
    
    private function formatDaysToCalendarForm($days, $daysInMonth) 
    {
        $daysArray = [];
        
        for ($i = 0; $i < count($days); $i++)
        {
            if ($i == 0)
            {
                $monthStart = $days[$i]->number_in_week;
                
                if ($monthStart != 1)
                {
                    for ($j = 1; $j < $monthStart; $j++)
                    {
                        $daysArray[] = [];
                    }
                }
                
                $daysArray[] = $days[$i];
                
                if ($monthStart == 6)
                {
                    $daysArray[] = [];
                }
            }
            else
            {
                $daysArray[] = $days[$i];
                
                if ($days[$i]->number_in_week == 6)
                {
                    $daysArray[] = [];
                }
            }
        }
        
        return $daysArray;
    }
}
