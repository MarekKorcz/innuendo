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
     * Activate calendar.
     *
     * @param  int calendar_id
     * 
     * @return Response
     */
    public function activate(Request $request, $calendar_id)
    {
        $calendar = Calendar::find($calendar_id);
        
        $calendar->isActive = 1;
        $calendar->save();
        
        $property_id = $request->property_id;
            
        return redirect()->action(
            'PropertyController@show', [
                'id' => $property_id
            ]
        )->with('success', 'Calendar has been successfully activated!');
    }
    
    /**
     * Deactivate calendar.
     *
     * @param  int calendar_id
     * 
     * @return Response
     */
    public function deactivate(Request $request, $calendar_id)
    {
        $calendar = Calendar::find($calendar_id);
        
        $calendar->isActive = 0;
        $calendar->save();
        
        $property_id = $request->property_id;
            
        return redirect()->action(
            'PropertyController@show', [
                'id' => $property_id
            ]
        )->with('success', 'Calendar has been successfully deactivated!');
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $calendar_id
     * @return Response
     */
    public function destroy(Request $request, $calendar_id)
    {        
        $calendar = Calendar::find($calendar_id);
        $calendar->delete();
        
        $property_id = $request->property_id;
        
        return redirect()->action(
            'PropertyController@show', [
                'id' => $property_id
            ]
        )->with('success', 'Calendar deleted!');
    }
}
