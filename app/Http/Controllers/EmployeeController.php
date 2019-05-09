<?php

namespace App\Http\Controllers;

use App\Calendar;
use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Redirect;

class EmployeeController extends Controller
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
     * Show the form for assign an employee to calendar.
     *
     * @param  int  $id
     * @return Response
     */
    public function assign($id)
    {
        $calendar = Calendar::where('id', $id)->first();
        $employees = User::where('isEmployee', 1)->pluck('name', 'id');
        
        return view('employee.assign')->with([
            'calendar' => $calendar,
            'employees' => $employees
        ]);
    }

    /**
     * Store a newly assigned resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $rules = array(
            'employee'  => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return Redirect::to('employee/store')
                ->withErrors($validator);
        } else {
            // load employee
            $employee = User::where('id', Input::get('employee'))->first();
            
            // assign it to calendar
            $calendar = Calendar::where('id', Input::get('calendar_id'))->first();
            $calendar->employee_id = $employee->id;
            $calendar->save();

            return redirect()->action(
                'PropertyController@show', [
                    'id' => $calendar->property_id
                ]
            )->with('success', 'Employee successfully assigned!');
        }
    }
}
