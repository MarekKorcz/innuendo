<?php

namespace App\Mail;

use App\User;
use App\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SubscriptionPurchased extends Mailable
{
    use Queueable, SerializesModels;
    
    public $boss;
    public $subscription;
    public $loginUrl;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $boss, Subscription $subscription)
    {
        $this->boss = $boss;
        $this->subscription = $subscription;
        $this->loginUrl = route('login');
        $this->subject(
            \Lang::get('common.subscription_purchased') . ' ' . 
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
        return $this->markdown('emails.boss_subscription_purchased');
    }
}
