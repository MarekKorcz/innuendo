<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserCreateWithPromoCode extends Mailable
{
    use Queueable, SerializesModels;
    
    public $user;
    public $boss;
    public $loginUrl;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, User $boss)
    {
        $this->user = $user;
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
        return $this->markdown('emails.user_create_with_promo_code');
    }
}
