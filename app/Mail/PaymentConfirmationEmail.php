<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentConfirmationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $emailData;

    public function __construct(array $emailData)
    {
        $this->emailData = $emailData;
    }

    public function build(): self
    {
        $receiptNumber = $this->emailData['receiptNumber'];
        $memberName = $this->emailData['member']->first_name . ' ' . $this->emailData['member']->last_name;
        
        // Créer le PDF receipt
        $payment = $this->emailData['payment'];
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.payment-invoice', compact('payment'));
        $filename = 'recu-' . $receiptNumber . '.pdf';
        
        return $this->subject("Confirmation de paiement – Reçu N°{$receiptNumber} – MyFitness Gym")
            ->view('emails.payment-confirmation')
            ->with($this->emailData)
            ->attachData($pdf->output(), $filename, [
                'mime' => 'application/pdf',
            ]);
    }
}
