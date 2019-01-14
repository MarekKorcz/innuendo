<?php

namespace App\Http\Controllers;

use App\Calendar;
use App\Property;

class CalendarController extends Controller
{
    /**
     * Create calendar.
     *
     * @param  int  $id
     * 
     * @return Response
     */
    public function create($id)
    {
        $property = Property::find($id);
        
        $calendar = new Calendar();
        $calendar->property_id = $property->id;
        $calendar->save();
        
        return view('property.show')->with('property', $property)->with('calendar', $calendar);
    }
}
