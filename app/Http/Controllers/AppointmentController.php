<?php

namespace App\Http\Controllers;

use App\Graphic;
use App\Appointment;
use App\Item;
use App\Year;
use App\Month;
use App\Day;
use App\Mail\AppointmentStore;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class AppointmentController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['beforeShowCreatePage']);
    }
    
    /**
     * An intermediate method which decides whether user has to log in or is already logged in.
     * 
     * @param Request $request
     * 
     * @return type
     */
    public function beforeShowCreatePage(Request $request)
    {
        if ($request->appointmentTerm && 
            $request->graphicId)
        {
            session([
                'appointmentTerm' => $request->appointmentTerm,
                'graphicId' => $request->graphicId
            ]);
            
            if (auth()->user() !== null)
            {
                return redirect()->action(
                    'AppointmentController@create'
                );
                
            } else {
                
                return redirect()->route('login');
            }
        }
        
        return redirect()->route('welcome');
    }

    /**
     * Show the form for creating a new resource.
     * 
     * @param Request $request
     * 
     * @return type
     */
    public function create(Request $request)
    {
        if ($request->session()->get('appointmentTerm') !== null && is_integer((int)$request->session()->get('appointmentTerm')) &&
            $request->session()->get('graphicId') !== null && is_integer((int)$request->session()->get('graphicId')))
        {           
            $explodedAppointmentTerm = explode(":", $request->session()->get('appointmentTerm'));
            
            if (count($explodedAppointmentTerm) == 2)
            {
                $graphicId = htmlentities($request->session()->get('graphicId'), ENT_QUOTES, "UTF-8");
                $appointmentTerm = htmlentities($request->session()->get('appointmentTerm'), ENT_QUOTES, "UTF-8");
                
                $request->session()->forget('graphicId');
                $request->session()->forget('appointmentTerm');
                
                $graphic = Graphic::where('id', $graphicId)->with('day.month.year.property')->first();
                
                if ($graphic !== null)
                {
                    $startTime = date('G:i', strtotime($graphic->start_time));
                    $endTime = date('G:i', strtotime($graphic->end_time));
                    $appointmentTerm = date('G:i', strtotime($appointmentTerm));
                    
                    $startTimeDateTimeObejct = new \DateTime($startTime);
                    $endTimeDateTimeObject = new \DateTime($endTime);
                    $appointmentTermDateTimeObject = new \DateTime($appointmentTerm);

                    if ($appointmentTermDateTimeObject >= $startTimeDateTimeObejct && $appointmentTermDateTimeObject < $endTimeDateTimeObject)
                    {
                        $chosenAppointment = Appointment::where([
                            'graphic_id' => $graphicId,
                            'start_time' => $appointmentTerm
                        ])->first();

                        if ($chosenAppointment == null)
                        {
                            $appointmentLength = 1;
                            $appointmentTermIncremented = $appointmentTermDateTimeObject;

                            for ($i = 0; $i < 5; $i++)
                            {
                                $appointmentTermIncremented = new \DateTime(date('G:i', strtotime("+20 minutes", strtotime($appointmentTermIncremented->format('G:i')))));

                                if ($appointmentTermIncremented >= $startTimeDateTimeObejct && $appointmentTermIncremented < $endTimeDateTimeObject)
                                {
                                    $nextAppointmentAvailable = Appointment::where([
                                        'graphic_id' => $graphicId,
                                        'start_time' => $appointmentTermIncremented->format('G:i')
                                    ])->first();

                                    if ($nextAppointmentAvailable === null)
                                    {
                                        $appointmentLength += 1;

                                    } else {

                                        break;
                                    }

                                } else {

                                    break;
                                }
                            }
                            
                            $items = Item::where('minutes', '<=', $appointmentLength * 20)->get();

                            return view('appointment.create')->with([
                                'appointmentTerm' => $appointmentTerm,
                                'propertyId' => $graphic->day->month->year->property->id,
                                'graphicId' => $graphic->id,
                                'year' => $graphic->day->month->year->year,
                                'month' => $graphic->day->month->month_number,
                                'day' => $graphic->day->day_number,
                                'items' => count($items) > 0 ? $items->sortBy('minutes') : []
                            ]);

                        } else {

                            $message = 'Wizyta jest już zajęta';
                        }

                    } else {

                        $message = 'Niepoprawny termin wizyty';
                    }

                    if (auth()->user()->isBoss)
                    {
                        return redirect()->action(
                            'BossController@calendar', [
                                'property_id' => $graphic->day->month->year->property->id,
                                'year' => $graphic->day->month->year->year, 
                                'month_number' => $graphic->day->month->month_number, 
                                'day_number' => $graphic->day->day_number
                            ]
                        )->with('error', $message);
                        
                    } else {
                        
                        return redirect()->action(
                            'UserController@calendar', [
                                'property_id' => $graphic->day->month->year->property->id,
                                'year' => $graphic->day->month->year->year, 
                                'month_number' => $graphic->day->month->month_number, 
                                'day_number' => $graphic->day->day_number
                            ]
                        )->with('error', $message);
                    }
                }

                return redirect()->route('welcome')->with('error', 'Grafik nie istnieje');
            }            
        }
        
        return redirect()->route('welcome');
    }
    
    /**
     * Store a newly created resource in storage.
     * 
     * @param Request $request
     * @return type
     */
    public function store(Request $request)
    {        
        $appointmentTerm = $request->get('appointmentTerm');
        $item = $request->get('item');
        $propertyId = $request->get('propertyId');
        $graphicId = $request->get('graphicId');
        $year = $request->get('year');
        $month = $request->get('month');
        $day = $request->get('day');
        
        if ($appointmentTerm !== null &&
            $item !== null && is_integer((int)$item) &&
            $propertyId !== null && is_integer((int)$propertyId) &&
            $graphicId !== null && is_integer((int)$graphicId) &&
            $year !== null && is_integer((int)$year) &&
            $month !== null && is_integer((int)$month) &&
            $day !== null && is_integer((int)$day))
        {        
            $explodedAppointmentTerm = explode(":", $appointmentTerm);
            
            if (count($explodedAppointmentTerm) == 2)
            {
                $appointmentTerm = htmlentities($appointmentTerm, ENT_QUOTES, "UTF-8");
                $item = htmlentities($item, ENT_QUOTES, "UTF-8");
                $propertyId = htmlentities($propertyId, ENT_QUOTES, "UTF-8");
                $graphicId = htmlentities($graphicId, ENT_QUOTES, "UTF-8");
                $year = htmlentities($year, ENT_QUOTES, "UTF-8");
                $month = htmlentities($month, ENT_QUOTES, "UTF-8");
                $day = htmlentities($day, ENT_QUOTES, "UTF-8");
                
                $user = auth()->user();
                $item = Item::where('id', $item)->first();
                
                if ($item !== null && $this->checkIfStillCanMakeAnAppointment($graphicId, $appointmentTerm, $item->minutes))
                {
                    $plusTime = "+" . $item->minutes . " minutes";
                    $endTime = date('G:i', strtotime($plusTime, strtotime($appointmentTerm)));
                    
                    $year = Year::where([
                        'year' => $year,
                        'property_id' => $propertyId
                    ])->first();
                    
                    $month = Month::where([
                        'month_number' => $month,
                        'year_id' => $year->id
                    ])->first();
                    
                    $day = Day::where([
                        'day_number' => $day,
                        'month_id' => $month->id
                    ])->first();
                    
                    $appointment = new Appointment();
                    $appointment->start_time = $appointmentTerm;
                    $appointment->end_time = $endTime;
                    $appointment->minutes = $item->minutes;
                    $appointment->graphic_id = $graphicId;
                    $appointment->day_id = $day->id;
                    $appointment->user_id = $user->id;
                    $appointment->item_id = $item->id;
                    $appointment->save();
                    
                    \Mail::to($user)->send(new AppointmentStore($user, $appointment));
                    
                    return redirect()->action(
                        'UserController@appointmentShow', [
                            'id' => $appointment->id
                        ]
                    )->with('success', 'Wizyta została zarezerwowana. Informacja potwierdzająca została wysłana na maila!');
                }
                
                if ($user->isBoss)
                {
                    return redirect()->action(
                        'BossController@calendar', [
                            'propertyId' => $propertyId,
                            'year' => $year,
                            'month_number' => $month,
                            'day_number' => $day
                        ]
                    )->with('error', 'Nie można zarezerwować wizyty! Być może jest już zajęta!');
                
                } else {
                    
                    return redirect()->action(
                        'UserController@calendar', [
                            'propertyId' => $propertyId,
                            'year' => $year,
                            'month_number' => $month,
                            'day_number' => $day
                        ]
                    )->with('error', 'Nie można zarezerwować wizyty! Być może jest już zajęta!');
                }
            }
        }
        
        return redirect()->route('welcome')->with('error', 'Coś poszło nie tak');
    }
    
    /**
     * Checks if man still can make an appointment (code from create method)
     * 
     * @param type $graphicId
     * @param type $appointmentTerm
     * @param type $itemLength
     * 
     * @return boolean|int
     */
    private function checkIfStillCanMakeAnAppointment($graphicId, $appointmentTerm, $itemLength)
    {
        $graphic = Graphic::where('id', $graphicId)->first();
        
        if ($graphic !== null)
        {
            $startTime = date('G:i', strtotime($graphic->start_time));
            $endTime = date('G:i', strtotime($graphic->end_time));
            $appointmentTerm = date('G:i', strtotime($appointmentTerm));
            
            $startTimeDateTimeObject = new \DateTime($startTime);
            $endTimeDateTimeObject = new \DateTime($endTime);
            $appointmentTermDateTimeObject = new \DateTime($appointmentTerm);

            if ($appointmentTermDateTimeObject >= $startTimeDateTimeObject && $appointmentTermDateTimeObject < $endTimeDateTimeObject)
            {
                $chosenAppointment = Appointment::where([
                    'graphic_id' => $graphicId,
                    'start_time' => $appointmentTerm
                ])->first();

                if ($chosenAppointment == null)
                {
                    $appointmentLength = [
                        0 => true
                    ];
                    $appointmentTermIncremented = $appointmentTermDateTimeObject;

                    for ($i = 0; $i < 5; $i++)
                    {
                        $appointmentTermIncremented = new \DateTime(date('G:i', strtotime("+20 minutes", strtotime($appointmentTermIncremented->format('G:i')))));

                        if ($appointmentTermIncremented >= $startTimeDateTimeObject && $appointmentTermIncremented < $endTimeDateTimeObject)
                        {
                            $nextAppointmentAvailable = Appointment::where([
                                'graphic_id' => $graphicId,
                                'start_time' => $appointmentTermIncremented->format('G:i')
                            ])->first();

                            if ($nextAppointmentAvailable === null)
                            {
                                $appointmentLength[] = true;
                                
                            } else {
                                
                                $appointmentLength[] = false;
                            }
                            
                        } else {
                            
                            $appointmentLength[] = false;
                        }
                    }
                    
                    $itemLength = $itemLength / 20;
                    
                    for ($i = 0; $i < $itemLength; $i++)
                    {
                        if ($appointmentLength[$i] == false)
                        {
                            return false;
                        }
                    }
                    
                    return true;
                }
            }
        }
        
        return false;
    }
}
