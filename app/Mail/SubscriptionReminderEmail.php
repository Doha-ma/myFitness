<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SubscriptionReminderEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $member;
    public $lastPayment;
    public $subscriptionType;
    public $daysUntilExpiry;

    /**
     * Create a new message instance.
     */
    public function __construct($member, $lastPayment, $subscriptionType, $daysUntilExpiry)
    {
        $this->member = $member;
        $this->lastPayment = $lastPayment;
        $this->subscriptionType = $subscriptionType;
        $this->daysUntilExpiry = $daysUntilExpiry;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(config('mail.from.address'), config('mail.from.name')),
            subject: 'Rappel d\'expiration d\'abonnement - ' . config('app.name'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.subscription-reminder',
            with: [
                'member' => $this->member,
                'lastPayment' => $this->lastPayment,
                'subscriptionType' => $this->subscriptionType,
                'daysUntilExpiry' => $this->daysUntilExpiry,
                'expiryDate' => $this->lastPayment->payment_date->addDays($this->subscriptionType->duration_days),
                'renewalPrice' => $this->subscriptionType->formatted_price,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
