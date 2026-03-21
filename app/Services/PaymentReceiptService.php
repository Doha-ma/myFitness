<?php

namespace App\Services;

use App\Mail\PaymentReceiptMail;
use App\Models\Payment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class PaymentReceiptService
{
    /**
     * Generate the PDF receipt, store it, and deliver it to the member when possible.
     *
     * @return array{emailed: bool, storage_path: string, filename: string}
     */
    public function deliver(Payment $payment): array
    {
        $payment->loadMissing([
            'member',
            'member.etablissement',
            'subscriptionType',
            'receptionist',
            'etablissement',
        ]);

        $paymentHistory = $payment->member->payments()
            ->orderByDesc('payment_date')
            ->orderByDesc('id')
            ->take(10)
            ->get();

        $etablissement = $payment->etablissement ?? $payment->member->etablissement;

        $pdf = Pdf::loadView('pdf.payment-invoice', [
            'payment' => $payment,
            'etablissement' => $etablissement,
            'paymentHistory' => $paymentHistory,
        ])->setPaper('a4');

        $binary = $pdf->output();
        $filename = 'recu-' . str_pad((string) $payment->id, 6, '0', STR_PAD_LEFT) . '.pdf';
        $storagePath = "receipts/{$filename}";

        Storage::disk('local')->put($storagePath, $binary);

        $emailed = false;
        if ($this->mailIsConfigured() && !empty($payment->member->email)) {
            Mail::to($payment->member->email)
                ->send(new PaymentReceiptMail($payment, $binary, $filename));
            $emailed = true;
        }

        return [
            'emailed' => $emailed,
            'storage_path' => $storagePath,
            'filename' => $filename,
        ];
    }

    private function mailIsConfigured(): bool
    {
        $defaultMailer = config('mail.default');
        $mailers = config('mail.mailers', []);

        return !empty($defaultMailer) && array_key_exists($defaultMailer, $mailers ?? []);
    }
}
