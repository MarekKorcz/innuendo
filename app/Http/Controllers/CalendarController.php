<?php

namespace App\Http\Controllers;

use App\Calendar;
use App\Property;

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
        
        $calendar = Calendar::firstOrCreate([
            'property_id' => $property->id
        ]);
            
        return view('property.show')->with('property', $property)->with('calendar', $calendar)->with('years', []);
    }
}
