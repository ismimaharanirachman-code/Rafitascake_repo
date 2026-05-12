<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;

class InvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    public $pdfContent;

    public function __construct($data, $pdfContent)
    {
        $this->data = $data;
        $this->pdfContent = $pdfContent;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Invoice Rafitas Cake',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.invoice',
            with: [
                'data' => $this->data,
            ],
        );
    }

    public function attachments(): array
    {
        return [
            Attachment::fromData(
                fn () => $this->pdfContent,
                'invoice.pdf'
            )->withMime('application/pdf'),
        ];
    }
}