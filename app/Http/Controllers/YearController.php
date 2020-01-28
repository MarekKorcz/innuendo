<?php

namespace App\Http\Controllers;

use App\Property;
use App\Year;
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
        $property = Property::where('id', $id)->first();
        
        return view('year.create')->with('property', $property);
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
            'year' => 'required',
            'property_id' => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        // process the login
        if ($validator->fails()) {
            return Redirect::to('year/create')
                ->withErrors($validator);
        } else {
            
            $property = Property::where('id', Input::get('property_id'))->with([
                'years'
            ])->first();
            
            if ($property !== null)
            {
                $givenYear = Input::get('year');
                $year = null;
                
                if (count($property->years) > 0)
                {
                    foreach ($property->years as $propertyYear)
                    {
                        if ($propertyYear->year == $givenYear)
                        {
                            $year = $propertyYear;
                        }
                    }
                }
                
                if ($year == null)
                {
                    $year = new Year();
                    $year->year = $givenYear;
                    $year->property_id = $property->id;
                    $year->save();
                }

                return redirect()->action(
                    'YearController@show', [
                        'id' => $year->id
                ])->with('success', 'Year successfully created!');
            }
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
        $year = Year::where('id', $id)->with([
            'months',
            'property'
        ])->first();
        
        return view('year.show')->with([
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
        $year = Year::where('id', $id)->with('property')->first();
        
        if ($year !== null)
        {
            $property = $year->property;

            $year->delete();

            return redirect()->action(
                'PropertyController@show', [
                    'id' => $property->id
            ])->with('success', 'Year has been successfully deleted!');
        }
    }
}
