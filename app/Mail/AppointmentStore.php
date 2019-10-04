<?php

namespace App\Mail;

use App\User;
use App\Day;
use App\Month;
use App\Year;
use App\Calendar;
use App\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Session;

class AppointmentStore extends Mailable
{
    use Queueable, SerializesModels;
    
    public $user;
    public $appointment;
    public $loginUrl;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, Appointment $appointment)
    {
        $this->user = $user;
        $this->appointment = $appointment;
        $this->loginUrl = route('login');
        
        $this->appointment['day'] = null;
        $this->appointment['month'] = null;
        $this->appointment['year'] = null;
        $this->appointment['property'] = null;
        
        $appointmentDay = Day::where('id', $this->appointment->day_id)->first();
        
        if ($appointmentDay !== null)
        {        
            $this->appointment['day'] = $appointmentDay->day_number;
            $appointmentMonth = Month::where('id', $appointmentDay->month_id)->first();
            
            if ($appointmentMonth !== null)
            {
                if (Session::get('locale') == "pl")
                {
                    $this->appointment['month'] = $appointmentMonth->month;
                    
                } else if (Session::get('locale') == "en") {
                    
                    $this->appointment['month'] = $appointmentMonth->month_en;
                }
                
                $appointmentYear = Year::where('id', $appointmentMonth->year_id)->first();
                
                if ($appointmentYear !== null)
                {
                    $this->appointment['year'] = $appointmentYear->year;
                    
                    $calendar = Calendar::where('id', $appointmentYear->calendar_id)->with('property')->first();
                    
                    if ($calendar !== null)
                    {
                        $this->appointment['property'] = $calendar->property;
                    }
                }
            }
        }
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.user_appointment_store');
    }
}
