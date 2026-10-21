<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrganizerRegistered extends Mailable
{
    use Queueable, SerializesModels;

    public $organizer;

    public function __construct($organizer)
    {
        $this->organizer = $organizer;
    }

    public function build()
    {
        return $this->subject('New organizer registration')->view('emails.organizer_registered');
    }
}
