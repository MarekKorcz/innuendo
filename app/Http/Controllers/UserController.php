<?php

namespace App\Http\Controllers;

use App\Calendar;
use App\Property;
use App\User;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('auth');
    }
    
    /**
     * Shows employees.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function employeesList()
    {
        $employees = User::where('isEmployee', 1)->get();
        
        return view('employee.index')->with('employees', $employees);
    }
    
    /**
     * Shows employee.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function employee($slack)
    {
        $employee = User::where('isEmployee', 1)->where('slack', $slack)->first();
        $calendars = Calendar::where('employee_id', $employee->id)->get();
        
        $properties = [];
        
        for ($i = 0; $i < count($calendars); $i++)
        {
            $properties[$i] = Property::where('id', $calendars[$i]->property_id)->first();
        }
        
        return view('employee.show')->with('employee', $employee)->with('calendars', $calendars)->with('properties', $properties);
    }
    
    /**
     * Shows calendar that belongs to employee.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function calendar($property_id, $calendar_id)
    {
        $calendar = Calendar::where('id', $calendar_id)->where('property_id', $property_id)->first();
        
//        dump($calendar);die;
        
        return view('calendar');
    }
}
