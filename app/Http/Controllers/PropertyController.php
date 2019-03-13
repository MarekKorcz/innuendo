<?php

namespace App\Http\Controllers;

use App\Property;
use App\Calendar;
use App\Year;
use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Redirect;

class PropertyController extends Controller
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
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $properties = Property::where('id', '>', 0)->paginate(5);

        return view('property.index')
            ->with('properties', $properties);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('property.create');
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
            'name'          => 'required',
            'description'   => 'required',
            'phone_number'  => 'required',
            'street'        => 'required',
            'street_number' => 'required',
            'house_number'  => 'required',
            'city'          => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        // process the login
        if ($validator->fails()) {
            return Redirect::to('property/create')
                ->withErrors($validator)
                ->withInput(Input::except('password'));
        } else {
            // store
            $property = new Property();
            $property->name          = Input::get('name');
            $property->slug          = str_slug(Input::get('name'));
            $property->description   = Input::get('description');
            $property->phone_number  = Input::get('phone_number');
            $property->street        = Input::get('street');
            $property->street_number = Input::get('street_number');
            $property->house_number  = Input::get('house_number');
            $property->city          = Input::get('city');
            $property->save();

            // redirect
            return redirect('/property/index')->with('success', 'Property successfully created!');
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
        $property = Property::find($id);
        $calendars = Calendar::where('property_id', $property->id)->get();
        
        $years = [];
        $employees = [];
        
        foreach ($calendars as $calendar)
        {
            if ($calendar)
            {
                $years[$calendar->id] = Year::where('calendar_id', $calendar->id)->orderBy('year', 'desc')->get();
                
                if ($calendar->employee_id != null)
                {
                    $employees[$calendar->id] = User::find($calendar->employee_id);
                }
            }
        }
        
        return view('property.show')->with([
            'property' => $property,
            'calendars' => $calendars,
            'years' => $years,
            'employees' => $employees
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $property = Property::find($id);
        
        return view('property.edit')->with('property', $property);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        // validate
        $rules = array(
            'name'          => 'required',
            'description'   => 'required',
            'phone_number'  => 'required',
            'street'        => 'required',
            'street_number' => 'required',
            'house_number'  => 'required',
            'city'          => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        // process the login
        if ($validator->fails()) {
            return Redirect::to('property/create')
                ->withErrors($validator)
                ->withInput(Input::except('password'));
        } else {
            // store
            $property = Property::find($id);
            $property->name          = Input::get('name');
            $property->slug          = str_slug(Input::get('name'));
            $property->description   = Input::get('description');
            $property->phone_number  = Input::get('phone_number');
            $property->street        = Input::get('street');
            $property->street_number = Input::get('street_number');
            $property->house_number  = Input::get('house_number');
            $property->city          = Input::get('city');
            $property->save();

            // redirect
            return redirect('/property/index')->with('success', 'Property successfully updated!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $property = Property::find($id);
        $property->delete();
        
        return redirect('/property/index')->with('success', 'Property deleted!');
    }
}
