<?php

namespace App\Mail;

use App\TempUser;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AdminTempEmployeeCreate extends Mailable
{
    use Queueable, SerializesModels;
    
    public $employee;
    public $tempEmployeeRegisterAddress;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(TempUser $employee)
    {
        $this->employee = $employee;
        
        if ($this->employee !== null)
        {
            $this->tempEmployeeRegisterAddress = route('tempEmployeeRegisterAddress', [
                'code' => $this->employee->register_code
            ]);
        }
        
        $this->subject(
            \Lang::get('common.register_1st_step') .
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
        return $this->markdown('emails.admin_temp_employee_create');
    }
}
