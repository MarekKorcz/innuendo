<?php

namespace App\Http\Controllers;

use App\Calendar;
use App\Property;
use App\Year;
use App\User;

class CalendarController extends Controller
{
    /**
     * Create calendar.
     *
     * @param  Property $id
     * 
     * @return Response
     */
    public function create($id)
    {
        $property = Property::find($id);
        
        $calendar = Calendar::create([
            'property_id' => $property->id
        ]);
        
        $calendars = Calendar::where('property_id', $property->id)->get();
        
        $years = [];
        $users = [];
        
        foreach ($calendars as $calendar)
        {
            if ($calendar)
            {
                $years[$calendar->id] = Year::where('calendar_id', $calendar->id)->orderBy('year', 'desc')->get();
                
                if ($calendar->employee_id != null)
                {
                    $users[$calendar->id] = User::find($calendar->employee_id);
                }
            }
        }
            
        return view('property.show')->with('property', $property)->with('calendars', $calendars)->with('years', $years)->with('users', $users);
    }
}
