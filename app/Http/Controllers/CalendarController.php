<?php

namespace App\Http\Controllers;

use App\Calendar;
use App\Property;
use Illuminate\Http\Request;

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
     * @return Response
     */
    public function destroy(Request $request, $calendar_id)
    {
        $property_id = $request->property_id;
        
        $calendar = Calendar::find($calendar_id);
        $calendar->delete();
        
        return redirect()->action(
            'PropertyController@show', [
                'id' => $property_id
            ]
        )->with('success', 'Calendar deleted!');
    }
}
