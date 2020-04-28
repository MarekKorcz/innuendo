<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Session;

class BioSendContactMessage extends Mailable
{
    use Queueable, SerializesModels;
    
    public $name;
    public $email;
    public $topic;
    public $description;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name, $email, $topic, $description)
    {
        $this->name = $name;
        $this->email = $email;
        $this->topic = $topic;
        $this->description = $description;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('bio.emails.send_contact_message');
    }
}
