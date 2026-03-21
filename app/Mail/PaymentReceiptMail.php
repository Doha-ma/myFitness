<?php

namespace App\Mail;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaymentReceiptMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Payment $payment,
        protected string $pdfBinary,
        protected string $filename
    ) {
        $this->payment->loadMissing(['member', 'etablissement', 'subscriptionType', 'receptionist']);
    }

    public function build(): self
    {
        $paymentNumber = str_pad((string) $this->payment->id, 6, '0', STR_PAD_LEFT);
        $subject = 'Votre recu #' . $paymentNumber . ' - ' . ($this->payment->etablissement->name ?? config('app.name'));

        return $this->subject($subject)
            ->view('emails.payment-receipt')
            ->with([
                'payment' => $this->payment,
                'etablissement' => $this->payment->etablissement,
            ])
            ->attachData(
                $this->pdfBinary,
                $this->filename,
                ['mime' => 'application/pdf']
            );
    }
}
