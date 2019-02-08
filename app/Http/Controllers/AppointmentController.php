<?php

namespace App\Http\Controllers;

use App\Graphic;
use App\Appointment;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Redirect;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['create']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Request $request)
    {
        $appointment_term = $request->appointmentTerm;
        $graphic_id = $request->graphicId;
        
        $calendar_id = $request->calendarId;
        $year = $request->year;
        $month = $request->month;
        $day = $request->day;
        
        $graphic = Graphic::find($graphic_id);
        
        if ($graphic !== null)
        {
            $startTime = $graphic->start_time;
            $endTime = $graphic->end_time;
            
            if ($appointment_term >= $startTime && $appointment_term < $endTime)
            {
                $chosenAppointment = Appointment::where('graphic_id', $graphic_id)->where('start_time', $appointment_term)->first();

                if ($chosenAppointment == null)
                {
                    $appointmentLength = 1;

                    for ($i = 0; $i < 2; $i++)
                    {
                        $appointment_term = date('G:i', strtotime("+30 minutes", strtotime($appointment_term)));
                        
                        if ($appointment_term >= $startTime && $appointment_term < $endTime)
                        {
                            $nextAppointmentAvailable = Appointment::where('graphic_id', $graphic_id)->where('start_time', $appointment_term)->first();

                            if ($nextAppointmentAvailable === null)
                            {
                                $appointmentLength += 1;
                            }
                            else
                            {
                                break;
                            }
                        }
                        else
                        {
                            break;
                        }
                    }

                    dump($appointmentLength);die;

                    return view('appointment.create');
                }
                else
                {
                    $message = 'Wizyta jest już zajęta';
                }
            }
            else
            {
                $message = 'Niepoprawny termin wizyty';
            }
        }
        else
        {
            $message = 'Grafik nie istnieje';
        }
        
        return redirect()->action(
            'UserController@calendar', [
                'calendar_id' => $calendar_id,
                'year' => $year, 
                'month_number' => $month, 
                'day_number' => $day
            ]
        )->with('error', $message);
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
            $property->user_id       = auth()->user()->id;
            $property->save();

            // redirect
            return redirect('/property/index')->with('success', 'Property successfully created!');
        }
    }
}
