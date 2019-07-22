<?php

namespace App\Mail;

use App\TempUser;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AdminTempBossCreate extends Mailable
{
    use Queueable, SerializesModels;
    
    public $boss;
    public $tempBossRegisterAddress;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(TempUser $boss)
    {
        $this->boss = $boss;
        
        if ($this->boss !== null)
        {
            $this->tempBossRegisterAddress = route('tempBossRegisterAddress', [
                'code' => $boss->register_code
            ]);
        }
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.admin_temp_boss_create');
    }
}
