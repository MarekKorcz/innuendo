<?php

namespace App\Mail;

use App\User;
use App\Day;
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
        
        $appointmentDay = Day::where('id', $this->appointment->day_id)->with('month.year.property')->first();
        
        if ($appointmentDay !== null)
        {        
            $this->appointment['day'] = $appointmentDay->day_number;
            
            if (Session::get('locale'))
            {
                if (Session::get('locale') == "pl")
                {
                    $this->appointment['month'] = $appointmentDay->month->month;

                } else if (Session::get('locale') == "en") {

                    $this->appointment['month'] = $appointmentDay->month->month_en;
                }
                
            } else {
                
                $browserDefaultLanguage = mb_substr(\Request::server('HTTP_ACCEPT_LANGUAGE'), 0, 2, 'utf-8');
                
                if ($browserDefaultLanguage == "pl")
                {
                    $this->appointment['month'] = $appointmentDay->month->month;

                } else if ($browserDefaultLanguage == "en") {

                    $this->appointment['month'] = $appointmentDay->month->month_en;
                }
            }
                
            $this->appointment['year'] = $appointmentDay->month->year->year;
            $this->appointment['property'] = $appointmentDay->month->year->property;
        }    
        
        $this->subject(
            \Lang::get('common.appointment_reservation') . ' ' .
            \Lang::get('common.in') . ' ' .
            config('app.name') . ' ' . config('app.name_2nd_part')
        );
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
