<?php

namespace App\Mail;

use App\User;
use App\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Session;

class AppointmentDestroy extends Mailable
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
        $appointment->load([
            'graphic.property',
            'day.month.year'
        ]);
        
        $this->user = $user;
        $this->appointment = $appointment;
        $this->loginUrl = route('login');
        
        $this->appointment['month'] = null;
        
        $appointmentMonth = $appointment->day->month;

        if (Session::get('locale'))
        {
            if (Session::get('locale') == "pl")
            {
                $this->appointment['month'] = $appointmentMonth->month;

            } else if (Session::get('locale') == "en") {

                $this->appointment['month'] = $appointmentMonth->month_en;
            }

        } else {

            $browserDefaultLanguage = mb_substr(\Request::server('HTTP_ACCEPT_LANGUAGE'), 0, 2, 'utf-8');

            if ($browserDefaultLanguage == "pl")
            {
                $this->appointment['month'] = $appointmentMonth->month;

            } else if ($browserDefaultLanguage == "en") {

                $this->appointment['month'] = $appointmentMonth->month_en;
            }
        }
        
        $this->appointment['property'] = $appointment->graphic->property;
        $this->appointment['year'] = $appointment->day->month->year->year;
        $this->appointment['day'] = $appointment->day->day_number;
                
        $this->subject(
            \Lang::get('common.appointment_removal') . ' ' .
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
        return $this->markdown('emails.user_appointment_destroy');
    }
}
