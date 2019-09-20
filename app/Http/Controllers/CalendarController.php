<?php

namespace App\Http\Controllers;

use App\Calendar;
use App\Property;
use Illuminate\Http\Request;

class CalendarController extends Controller
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
     * Create calendar.
     *
     * @param  Property $id
     * 
     * @return Response
     */
    public function create($id)
    {
        $property = Property::where('id', $id)->first();
        
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
        $calendar = Calendar::where('id', $calendar_id)->first();
        $property_id = $request->get('property_id');
        
        if ($calendar !== null && $calendar->property_id == $property_id)
        {
            $calendar->isActive = 1;
            $calendar->save();

            return redirect()->action(
                'PropertyController@show', [
                    'id' => $property_id
                ]
            )->with('success', 'Calendar has been successfully activated!');
        }
        
        return redirect()->route('welcome')->with('error', 'Such calendar doesn\'t exist');
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
        $calendar = Calendar::where('id', $calendar_id)->first();
        $property_id = $request->get('property_id');
        
        if ($calendar !== null && $calendar->property_id == $property_id)
        {
            $calendar->isActive = 0;
            $calendar->save();

            return redirect()->action(
                'PropertyController@show', [
                    'id' => $property_id
                ]
            )->with('success', 'Calendar has been successfully deactivated!');
        }
        
        return redirect()->route('welcome')->with('error', 'Such calendar doesn\'t exist');
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $calendarId
     * @return Response
     */
    public function destroy($calendarId)
    {        
        $calendar = Calendar::where('id', $calendarId)->first();
        
        if ($calendar !== null)
        {
            $propertyId = $calendar->property_id;         
            
            $calendar->delete();
            
            return redirect()->action(
                'PropertyController@show', [
                    'id' => $propertyId
                ]
            )->with('success', 'Calendar deleted!');
        }
        
        return redirect()->route('welcome')->with('error', 'Such calendar doesn\'t exist');
    }
}
