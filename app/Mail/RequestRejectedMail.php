<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RequestRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $request;
    public $reason;

    /**
     * Create a new message instance.
     */
    public function __construct($request, $reason)
    {
        $this->request = $request;
        $this->reason = $reason;
    }

    /**
     * Email subject
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Resident Letter Request Was Rejected ❌',
        );
    }

    /**
     * Email content
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.request-rejected',
            with: [
                'request' => $this->request,
                'reason' => $this->reason,
            ],
        );
    }

    /**
     * Attachments
     */
    public function attachments(): array
    {
        return [];
    }
}