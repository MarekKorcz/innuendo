<?php

namespace App\Http\Controllers;

use App\Calendar;
use App\Graphic;
use App\Property;
use App\User;
use App\Year;
use App\Month;
use App\Day;
use App\Appointment;
use App\Mail\AppointmentDestroy;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except([
//            'employeesList', 
//            'employee'
        ]);
    }
    
    /**
     * Shows employees.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function employeesList()
    {
        $employees = User::where('isEmployee', 1)->get();
        
        if ($employees !== null)
        {
            $employeesArray = [];

            for ($i = 0; $i < count($employees); $i++)
            {
                $employeesArray[$i + 1] = $employees[$i];
            }

            return view('employee.index')->with('employees', $employeesArray);
        }
        
        return redirect()->route('welcome');
    }
    
    /**
     * Shows employee.
     *
     * @param type $slug
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function employee($slug)
    {
        $employee = User::where([
            'isEmployee' => 1,
            'slug' => $slug
        ])->with('graphics.property')->first();
        
        if ($employee !== null)
        {
            $employeeCreatedAt = $employee->created_at->format('d.m.Y');
            $user = auth()->user();
            $bossId = null;
        
            if ($user->isBoss) 
            {    
                $bossId = $user->id;

            } else if ($user->boss_id !== null) {

                $bossId = $user->boss_id;
            }
            
            $employeeBossGraphicProperties = new Collection();
            
            if (count($employee->graphics) > 0)
            {
                $employee->graphics->each(function($item) use ($employeeBossGraphicProperties, $bossId, $user) 
                {
                    if (!$employeeBossGraphicProperties->contains('id', $item->property->id))
                    {
                        if (!$user->isAdmin)
                        {
                            if ($item->property->boss_id == $bossId)
                            {
                                $employeeBossGraphicProperties->push($item->property);
                            }

                        } else {

                            $employeeBossGraphicProperties->push($item->property);
                        }
                    }
                });
            }

            return view('employee.show')->with([
                'employee' => $employee,
                'employeeCreatedAt' => $employeeCreatedAt,
                'properties' => $employeeBossGraphicProperties,
                'user' => $user
            ]);
        }
        
        return redirect()->route('welcome');
    }
    
    /**
     * Shows properties.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function propertiesList()
    {
        $bossId = 0;
        $user = auth()->user();
        $bossProperties = new Collection();
        
        if ($user !== null)
        {
            if ($user->boss_id !== null)
            {
                $bossId = $user->boss_id;

            } else if ($user->isBoss !== null) {

                $bossId = $user->id;
            }

            if ($bossId !== 0)
            {
                $bossProperties = Property::where('boss_id', $bossId)->get();
            }
        }
        
        if (count($bossProperties) == 1)
        {
            return redirect()->action(
                'UserController@property', [
                    'id' => $bossProperties->first()->id,
                ]
            );
        }

        return view('user.property_index')->with([
            'properties' => $bossProperties
        ]);
    }
    
    /**
     * Shows calendar that belongs to employee.
     * 
     * @param integer $property_id
     * @param integer $year
     * @param integer $month_number
     * @param integer $day_number
     * 
     * @return type
     * @throws Exception
     */
    public function calendar($property_id, $year = 0, $month_number = 0, $day_number = 0)
    {
        $user = auth()->user();
        $properties = $user->getBossProperties();
        
        $property = null;
        
        if (count($properties) > 0)
        {
            $property = $properties->first(function ($item) use ($property_id) 
            {
                return $item->id == $property_id;
            });
        }
        
        if ($property !== null)
        {
            $currentDate = new \DateTime();
            
            if ($year == 0)
            {
                $year = Year::where([
                    'property_id' => $property->id,
                    'year' => $currentDate->format("Y")
                ])->first();
                
            } else if (is_numeric($year) && (int)$year > 0) {
                
                $year = Year::where([
                    'property_id' => $property->id,
                    'year' => $year
                ])->first();
            }
            
            if ($year !== null)
            {
                if ($month_number == 0)
                {
                    $month = Month::where([
                        'year_id' => $year->id,
                        'month_number' => $currentDate->format("n")
                    ])->first();
                    
                } else if (is_numeric($month_number) && (int)$month_number > 0 || (int)$month_number <= 12) {
                    
                    $month = Month::where([
                        'year_id' => $year->id,
                        'month_number' => $month_number
                    ])->first();
                }

                if ($month !== null)
                {
                    $days = Day::where('month_id', $month->id)->get();
                    
                    if (count($days) > 0)
                    {
                        $days = $this->formatDaysToUserCalendarForm($days, $month->days_in_month);

                        if ((int)$day_number === 0)
                        {
                            $currentDay = Day::where([
                                'month_id' => $month->id,
                                'day_number' => $currentDate->format("d")
                            ])->first();
                            
                        } else {
                            
                            $currentDay = Day::where([
                                'month_id' => $month->id,
                                'day_number' => $day_number
                            ])->first();
                        }
                        
                        if ($currentDay !== null)
                        {
                            $graphicTime = Graphic::where('day_id', $currentDay->id)->first();
                            $graphicTimes = Graphic::where('day_id', $currentDay->id)->with('employee')->get();
                            
                            $chosenDay = $currentDay;
                            $chosenDayDateTime = new \DateTime($year->year . "-" . $month->month_number . "-" . $chosenDay->day_number);
                            $graphic = $this->formatGraphicAndAppointments($graphicTime, $currentDay, $chosenDayDateTime);
                            
                        } else {
                            
                            $graphic = [];
                            $graphicTime = null;
                            $graphicTimes = null;
                        }
                        
                        
                        $availablePreviousMonth = false;

                        if ($this->checkIfPreviewMonthIsAvailable($property, $year, $month))
                        {
                            $availablePreviousMonth = true;
                        }
                        
                        $availableNextMonth = false;

                        if ($this->checkIfNextMonthIsAvailable($property, $year, $month))
                        {
                            $availableNextMonth = true;
                        }
                        
                        return view('user.calendar')->with([
                            'property' => $property,
                            'availablePreviousMonth' => $availablePreviousMonth,
                            'availableNextMonth' => $availableNextMonth,
                            'year' => $year,
                            'month' => $month,
                            'days' => $days,
                            'current_day' => is_object($currentDay) ? $currentDay->day_number : 0,
                            'current_day_id' => is_object($currentDay) ? $currentDay->id : 0,
                            'graphic' => $graphic,
                            'graphic_id' => $graphicTime !== null ? $graphicTime->id : null,
                            'graphicTimesEntites' => $graphicTimes
                        ]);
                        
                    } else {
                        
                        $message = 'Brak otwartego grafiku na ten dzień';
                    }
                    
                } else {
                    
                    $message = 'Brak otwartego grafiku na ten miesiąc';
                }
                
            } else {
                
                $message = 'Brak otwartego kalendarza na ten rok';
            }
            
            return redirect()->action(
                'UserController@propertiesList'
            )->with('error', $message);
            
        } else {
            
            $message = 'Niepoprawny numer id';
        }
        
        return redirect()->route('welcome')->with('error', $message);
    }
    
    public function getEmployeeGraphic(Request $request)
    {
        if ($request->get('graphicId') && $request->get('currentDayId'))
        {
            $graphic = Graphic::where('id', $request->get('graphicId'))->first();
            $day = Day::where('id', $request->get('currentDayId'))->with('month.year')->first();
            
            if ($graphic !== null && $day !== null)
            {
                $chosenDayDateTime = new \DateTime($day->month->year->year . "-" . $day->month->month_number . "-" . $day->day_number);
                $graphicArray = $this->formatGraphicAndAppointments($graphic, $day, $chosenDayDateTime);
                
                foreach ($graphicArray as $key => $graphArr)
                {
                    if ($graphArr['appointment'] !== null)
                    {
                        $graphicArray[$key]['appointmentId'] = $graphArr['appointment']->id;
                        $graphicArray[$key]['ownAppointmentHref'] = route('appointmentShow', [
                            'id' => $graphArr['appointment']->id
                        ]);
                        
                        unset($graphicArray[$key]['appointment']);
                    }
                }
                
                return new JsonResponse([
                    'type' => 'success',
                    'graphic' => $graphicArray,
                    'userId' => auth()->user()->id,
                    'appointmentDetailsDescription' => \Lang::get('common.appointment_details'),
                    'appointmentBookedDescription' => \Lang::get('common.booked'),
                    'availableDescription' => \Lang::get('common.available'),
                    'clickToMakeReservationDescription' => \Lang::get('common.click_to_make_reservation')
                ], 200, array(), true);
            }
        }
        
        return new JsonResponse(array(
            'type'    => 'error'        
        ));
    }
    
    /**
     * Shows an appointment assigned to current user.
     * 
     * @param type $id
     * @return type
     */
    public function appointmentShow($id)
    {
        $appointment = Appointment::where([
            'id' => $id,
            'user_id' => auth()->user()->id
        ])->with([
            'item',
            'day.month.year.property',
            'graphic.employee'
        ])->first();
        
        if ($appointment !== null)
        {
            $now = new \DateTime(date('Y-m-d H:i:s'));
            
            // appointment date                           
            $appointmentDay = (string)$appointment->day->day_number;
            $appointmentDay = strlen($appointmentDay) == 1 ? '0' . $appointmentDay : $appointmentDay;
            $appointmentMonth = (string)$appointment->day->month->month_number;
            $appointmentMonth = strlen($appointmentMonth) == 1 ? '0' . $appointmentMonth : $appointmentMonth;
            $appointmentDate = new \DateTime($appointment->day->month->year->year . '-' . $appointmentMonth . '-' . $appointmentDay . ' ' . $appointment->start_time);

            return view('user.appointment_show')->with([
                'appointment' => $appointment,
                'day' => $appointment->day->day_number,
                'month' => $appointment->day->month->month,
                'month_number' => $appointment->day->month->month_number,
                'year' => $appointment->day->month->year->year,
                'employee' => $appointment->graphic->employee,
                'property' => $appointment->day->month->year->property,
                'canBeDeleted' => $now < $appointmentDate ? true : false,
                'user' => auth()->user()
            ]);
        }
        
        return redirect()->route('welcome');
    }
    
    /**
     * Shows a list of appointments assigned to current user.
     * 
     * @return type
     */
    public function appointmentIndex()
    {
        $user = auth()->user();
        
//        $user->load('properties');
//        $properties = $user->properties;
//        
//        if (count($properties) == 0)
//        {
//            $properties = $user->getBossProperties();
//        }
        
        $appointments = Appointment::where('user_id', $user->id)->with([
            'item',
            'day.month.year.property',
            'graphic.employee',
            'user'
        ])->get();
            
        if (count($appointments) > 0)
        {
            foreach ($appointments as $appointment)
            {
                $appointment['year'] = $appointment->day->month->year->year;
                $appointment['month'] = $appointment->day->month->month;
                $appointment['month_en'] = $appointment->day->month->month_en;
                $appointment['month_number'] = $appointment->day->month->month_number;
                $appointment['day_number'] = $appointment->day->day_number;
                
                // appointment date                           
                $appointmentDay = (string)$appointment['day_number'];
                $appointmentDay = strlen($appointmentDay) == 1 ? '0' . $appointmentDay : $appointmentDay;
                $appointmentMonth = (string)$appointment['month_number'];
                $appointmentMonth = strlen($appointmentMonth) == 1 ? '0' . $appointmentMonth : $appointmentMonth;
                $appointment['date_time'] = new \DateTime($appointment['year'] . '-' . $appointmentMonth . '-' . $appointmentDay . ' ' . $appointment->start_time);
                $appointment['date_timestamps'] = strtotime($appointment['date_time']->format('Y-m-d H:i:s'));
                
                $appointment['date'] = $appointment['day_number'] . ' ' . $appointment['month'] . ' ' . $appointment['year'];
                $appointment['date_en'] = $appointment['day_number'] . ' ' . $appointment['month_en'] . ' ' . $appointment['year'];
                
                $appointment['property'] = $appointment->day->month->year->property;
                $appointment['address'] = $appointment['property']->street . ' ' . $appointment['property']->street_number . '/' . $appointment['property']->house_number . ', ' . $appointment['property']->city;
                
                $employee = $appointment->graphic->employee;
                $appointment['employee_name'] = $employee->name . " " . $employee->surname;
                $appointment['employee_slug'] = $employee->slug;
            }            
        }
        
        return view('user.appointment_index')->with([
            'appointments' => $appointments->sortByDesc('date_timestamps'),
            'user' => $user
        ]);
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function appointmentDestroy($id)
    {        
        $user = auth()->user();
        $appointment = Appointment::where('id', $id)->with([
            'user'
        ])->first();
        
        if ($appointment !== null && $appointment->user->id == $user->id || $user->isAdmin || $user->isEmployee)
        {            
            if ($appointment->status !== 1)
            {
                $appointment->delete();
                
                if ($user->id == $appointment->user->id)
                {
                    \Mail::to($user)->send(new AppointmentDestroy($user, $appointment));

                    return redirect()->action(
                        'UserController@appointmentIndex'
                    )->with('success', 'Wizyta została usunięta!');
                    
                } else {
                    
                    return redirect()->action(
                        'WorkerController@backendAppointmentIndex', [
                            'id' => $appointment->user->id
                    ])->with('success', 'Wizyta została usunięta!');
                }
            }
            
            return redirect()->route('welcome')->with('error', 'Nie można usunąć już wykonanej wizyty');
        }
        
        return redirect()->route('welcome')->with('error', 'Wizyta o podanym id nie istnieje');
    }

    private function formatDaysToUserCalendarForm($days, $daysInMonth) 
    {        
        $daysArray = [];
        
        for ($i = 0; $i < count($days); $i++)
        {
            if ($i == 0)
            {
                $monthStart = $days[$i]->number_in_week;
                
                if ($monthStart != 1)
                {
                    for ($j = 1; $j < $monthStart; $j++)
                    {
                        $daysArray[] = [];
                    }
                }
            }
            
            $dayGraphicCount = Graphic::where('day_id', $days[$i]->id)->get();
            $days[$i]['dayGraphicCount'] = count($dayGraphicCount);
            
            $daysArray[] = $days[$i];
        }
        
        return $daysArray;
    }
    
    private function formatGraphicAndAppointments($graphicTime, $chosenDay, $chosenDayDateTime) 
    {
        $graphic = [];
        $user = auth()->user();
        
        if ($graphicTime !== null)
        {
            $timeZone = new \DateTimeZone("Europe/Warsaw");
            $now = new \DateTime(null, $timeZone);
                        
            $workUnits = ($graphicTime->total_time / 20);
            $startTime = date('G:i', strtotime($graphicTime->start_time));
                        
            for ($i = 0; $i < $workUnits; $i++) 
            {     
                $appointmentId = 0;
                $ownAppointment = false;
                
                $explodedStartTime = explode(":", $startTime);
                $chosenDayDateTime->setTime($explodedStartTime[0], $explodedStartTime[1], 0);
                
                $appointment = Appointment::where([
                    'day_id' => $chosenDay->id,
                    'graphic_id' => $graphicTime->id,
                    'start_time' => $startTime
                ])->with('user')->first();
                
                if ($appointment !== null)
                {
                    $appointmentId = $appointment->id;
                    $ownAppointment = $appointment->user_id == $user->id ? true : false;
                    
                    $limit = $appointment->minutes / 20;
                    
                    if ($limit > 1)
                    {
                        $time = array($startTime);

                        for ($j = 1; $j < $limit; $j++)
                        {
                            $time[] = date('G:i', strtotime("+20 minutes", strtotime($time[count($time) - 1])));
                            $workUnits -= 1;
                        }
                        
                    } else {
                        
                        $time = $startTime;
                    }
                    
                    $graphic[] = [
                        'time' => $time,
                        'appointment' => $appointment,
                        'appointmentLimit' => $limit,
                        'appointmentId' => $appointmentId,
                        'ownAppointment' => $ownAppointment,
                        'canMakeAnAppointment' => $chosenDayDateTime > $now ? true : false
                    ];
                    
                    $timeIncrementedByAppointmentMinutes = strtotime($appointment->end_time, strtotime($startTime));
                    $startTime = date('G:i', $timeIncrementedByAppointmentMinutes);
                    
                } else {
                    
                    $graphic[] = [
                        'time' => $startTime,
                        'appointment' => null,
                        'appointmentLimit' => 0,
                        'appointmentId' => $appointmentId,
                        'ownAppointment' => $ownAppointment,
                        'canMakeAnAppointment' => $chosenDayDateTime > $now ? true : false
                    ];
                    
                    $timeIncrementedBy20Minutes = strtotime("+20 minutes", strtotime($startTime));
                    $startTime = date('G:i', $timeIncrementedBy20Minutes);
                }
            }         
        }
        
        return $graphic;
    }
    
    private function checkIfPreviewMonthIsAvailable($property, $year, $month)
    {
        if ($month->month_number == 1)
        {
            $year = Year::where([
                'property_id' => $property->id,
                'year' => ($year->year - 1)
            ])->first();
            
            if ($year === null)
            {
                return false;
                
            } else {
                
                $month = Month::where([
                    'year_id' => $year->id,
                    'month_number' => 12
                ])->first();
                
                if ($month === null) 
                {
                    return false;
                }
            }
            
        } else {
            
            $month = Month::where([
                'year_id' => $year->id,
                'month_number' => ($month->month_number - 1)
            ])->first();
                
            if ($month === null) 
            {
                return false;
            }
        }
        
        return true;
    }
    
    private function checkIfNextMonthIsAvailable($property, $year, $month)
    {
        if ($month->month_number == 12)
        {
            $year = Year::where([
                'property_id' => $property->id,
                'year' => ($year->year + 1)
            ])->first();
            
            if ($year === null)
            {
                return false;
                
            } else {
                
                $month = Month::where([
                    'year_id' => $year->id,
                    'month_number' => 1
                ])->first();
                
                if ($month === null) 
                {
                    return false;
                }
            }
            
        } else {
            
            $month = Month::where([
                'year_id' => $year->id,
                'month_number' => ($month->month_number + 1)
            ])->first();
                
            if ($month === null) 
            {
                return false;
            }
        }
        
        return true;
    }
}
