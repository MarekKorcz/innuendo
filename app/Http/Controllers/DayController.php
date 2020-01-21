<?php

namespace App\Http\Controllers;

use App\Month;
use App\Day;
use App\Graphic;
use App\GraphicRequest;
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
            
            $month = Month::where('id', Input::get('month_id'))->with('year')->first();
            
            if ($month !== null)
            {
                $yearNumber = $month->year->year;
                $monthNumber = strlen($month->month_number) == 2 ? $month->month_number : "0" . $month->month_number;

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
                            $day = new Day();
                            $day->day_number = $dayDate->format("j");
                            $day->number_in_week = $dayDate->format("N");
                            $day->month_id = Input::get('month_id');
                            $day->save();
                        }
                    }
                }

                return redirect()->action('MonthController@show', [
                    'id' => $month->id
                ])->with('success', 'Days have been successfully created!');
            }
        }
    }

    public function show($id)
    {
        $day = Day::where('id', $id)->with('month.year.property')->first();
        
        if ($day !== null)
        {            
            $graphicRequest = GraphicRequest::where([
                'day_id' => $day->id,
                'property_id' => $day->month->year->property_id
            ])->first();
            
            return view('day.show')->with([
                'day' => $day,
                'month' => $day->month,
                'year' => $day->month->year,
                'property' => $day->month->year->property,
                'graphicRequest' => $graphicRequest,
                'graphics' => Graphic::where('day_id', $day->id)->with('employee')->get()
            ]);
        }
        
        return redirect()->route('welcome');
    }
}
