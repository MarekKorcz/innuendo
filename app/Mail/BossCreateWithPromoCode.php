<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class BossCreateWithPromoCode extends Mailable
{
    use Queueable, SerializesModels;
    
    public $boss;
    public $loginUrl;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $boss)
    {
        $this->boss = $boss;
        $this->loginUrl = route('login');
        
        $this->subject(
            \Lang::get('common.completing_registration_in') . ' ' .
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
        return $this->markdown('emails.boss_create_with_promo_code');
    }
}
