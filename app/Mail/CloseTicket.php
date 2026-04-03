<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\File;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

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
            $path = $this->url;
            $extension = pathinfo($path, PATHINFO_EXTENSION);
            if (Storage::disk('s3')->exists($path)) {
                $fileContent = Storage::disk('s3')->get($path);
                $email->attachData($fileContent, 'Attachment.' . $extension);
            }
        }

        return $email;
    }
}
