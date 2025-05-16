<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;

class OrderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $user;

    /**
     * Create a new message instance.
     */
    public function __construct($order, $user)
    {
        $this->order = $order;
        $this->user = $user;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Confirmación de tu pedido',
            from: new Address('nosoyflix@gmail.com', 'DLG - Domótica, Luz y Gestión')
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.orderConfirmation',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
