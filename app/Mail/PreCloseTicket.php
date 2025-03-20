<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\File;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

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
            $absolutePath = $this->url;
            $extension = File::extension($absolutePath);
            $email->attach($absolutePath, ['as' => 'Attachment.' . $extension]);
        }

        return $email;
    }
}
