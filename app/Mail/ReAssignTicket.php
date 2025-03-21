<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\File;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReAssignTicket extends Mailable
{
    use Queueable, SerializesModels;

    public $dataTicket;
    public $assignToDept;
    public $messageContent;
    public $assignBy;

    public function __construct($dataTicket, $assignToDept, $messageContent, $assignBy)
    {
        $this->dataTicket = $dataTicket;
        $this->assignToDept = $assignToDept;
        $this->messageContent = $messageContent;
        $this->assignBy = $assignBy;
    }

    public function build()
    {
        //SUBJECT NAME
        $subject = "[RE ASSIGN TICKET] - ". strtoupper($this->dataTicket->priority) . " - " . $this->dataTicket->no_ticket;
        $email = $this->view('mail.reAssignTicket')->subject($subject);

        if ($this->dataTicket->file_1 != null) {
            $absolutePath = $this->dataTicket->file_1;
            $extension = File::extension($absolutePath);
            $email->attach($absolutePath, ['as' => 'Attachment.' . $extension]);
        }

        return $email;
    }
}
