<?php

namespace App\Mail;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TicketMail extends Mailable
{
    use Queueable, SerializesModels;

    public Ticket $ticket;
    public string $emailSubject;
    public string $emailMessage;

    public function __construct(Ticket $ticket, string $emailSubject, string $emailMessage)
    {
        $this->ticket       = $ticket;
        $this->emailSubject = $emailSubject;
        $this->emailMessage = $emailMessage;
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: $this->emailSubject);
    }

    public function content(): Content
    {
        return new Content(view: 'emails.ticket');
    }
}
