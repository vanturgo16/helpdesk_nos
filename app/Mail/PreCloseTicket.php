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

class PreCloseTicket extends Mailable
{
    use Queueable, SerializesModels;

    public $dataTicket;
    public $dataAssign;
    public $url;
    public $precloseBy;

    public function __construct($dataTicket, $dataAssign, $url, $precloseBy)
    {
        $this->dataTicket = $dataTicket;
        $this->dataAssign = $dataAssign;
        $this->url = $url;
        $this->precloseBy = $precloseBy;
    }

    public function build()
    {
        //SUBJECT NAME
        $subject = "[PRE CLOSE TICKET] - ". strtoupper($this->dataTicket->priority) . " - " . $this->dataTicket->no_ticket;
        $email = $this->view('mail.precloseTicket')->subject($subject);

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
