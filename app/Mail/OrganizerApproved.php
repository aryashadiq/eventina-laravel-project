<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrganizerApproved extends Mailable
{
    use Queueable, SerializesModels;

    public $organizer;

    public function __construct($organizer)
    {
        $this->organizer = $organizer;
    }

    public function build()
    {
        return $this->subject('Your organizer account has been approved')->view('emails.organizer_approved');
    }
}
