<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\File;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CloseTicket extends Mailable
{
    use Queueable, SerializesModels;

    public $dataTicket;
    public $assignToDept;
    public $url;
    public $closeBy;

    public function __construct($dataTicket, $assignToDept, $url, $closeBy)
    {
        $this->dataTicket = $dataTicket;
        $this->assignToDept = $assignToDept;
        $this->url = $url;
        $this->closeBy = $closeBy;
    }

    public function build()
    {
        //SUBJECT NAME
        $subject = "[TICKET CLOSED] - ". strtoupper($this->dataTicket->priority) . " - " . $this->dataTicket->no_ticket;
        $email = $this->view('mail.closeTicket')->subject($subject);

        if ($this->url != null) {
            $absolutePath = $this->url;
            $extension = File::extension($absolutePath);
            $email->attach($absolutePath, ['as' => 'Attachment.' . $extension]);
        }

        return $email;
    }
}
