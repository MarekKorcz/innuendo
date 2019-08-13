<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AdminTempEmployeeCreate2ndStep extends Mailable
{
    use Queueable, SerializesModels;
    
    public $employee;
    public $loginUrl;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $employee)
    {
        $this->employee = $employee;
        $this->loginUrl = route('login');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.admin_temp_employee_create_2nd_step');
    }
}
