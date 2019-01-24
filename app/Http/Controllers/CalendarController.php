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
            
        return redirect()->action(
            'PropertyController@show', [
                'id' => $id
            ]
        );
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $calendar_id
     * @param  int  $property_id
     * @return Response
     */
    public function destroy($calendar_id, $property_id)
    {
        $calendar = Calendar::find($calendar_id);
        $calendar->delete();
        
        return redirect('/property/index')->with('success', 'Calendar deleted!');
        
//        return redirect()->action(
//            'PropertyController@show', [
//                'id' => $property_id
//            ]
//        )->with('success', 'Calendar deleted!');
        
        // put it to view
//        {!!Form::open(['action' => ['CalendarController@destroy', [$calendar->id, $property->id]], 'method' => 'POST', 'class' => 'pull-right'])!!}
    }
}
