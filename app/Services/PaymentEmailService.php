<?php

namespace App\Services;

use App\Models\Payment;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Mail\PaymentConfirmationEmail;

class PaymentEmailService
{
    /**
     * Send payment confirmation email with receipt
     */
    public function sendPaymentConfirmationEmail(Payment $payment): array
    {
        try {
            // Check if member has email
            if (empty($payment->member->email)) {
                return [
                    'success' => false,
                    'message' => 'Email du membre manquant',
                    'error_type' => 'missing_email'
                ];
            }

            // Generate PDF receipt
            $pdfPath = $this->generateReceiptPDF($payment);
            
            // Prepare email data
            $emailData = [
                'payment' => $payment,
                'member' => $payment->member,
                'receiptNumber' => str_pad($payment->id, 6, '0', STR_PAD_LEFT),
                'receiptUrl' => route('payments.receipt', $payment->id),
                'receiptPdfPath' => $pdfPath
            ];

            // Send email
            Mail::to($payment->member->email)->send(new PaymentConfirmationEmail($emailData));
            
            // Clean up temporary PDF file
            if (file_exists($pdfPath)) {
                unlink($pdfPath);
            }

            // Log successful send
            Log::info('Payment confirmation email sent successfully', [
                'payment_id' => $payment->id,
                'member_email' => $payment->member->email,
                'receipt_number' => str_pad($payment->id, 6, '0', STR_PAD_LEFT)
            ]);

            return [
                'success' => true,
                'message' => 'Email envoyé avec succès',
                'sent_at' => now()->toISOString()
            ];

        } catch (\Exception $e) {
            Log::error('Failed to send payment confirmation email', [
                'payment_id' => $payment->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'message' => 'Échec de l\'envoi d\'email: ' . $e->getMessage(),
                'error_type' => 'send_failed',
                'error_details' => $e->getMessage()
            ];
        }
    }

    /**
     * Generate PDF receipt
     */
    private function generateReceiptPDF(Payment $payment): string
    {
        try {
            // Generate receipt HTML
            $html = View::make('payments.receipt', compact('payment'))->render();
            
            // Create PDF
            $pdf = Pdf::loadHTML($html)
                ->setPaper('a4')
                ->setOption('margin-top', '20mm')
                ->setOption('margin-bottom', '20mm')
                ->setOption('margin-left', '20mm')
                ->setOption('margin-right', '20mm');

            // Generate unique filename
            $filename = 'receipt-' . str_pad($payment->id, 6, '0', STR_PAD_LEFT) . '-' . now()->format('Y-m-d-His') . '.pdf';
            $path = storage_path('app/temp/' . $filename);
            
            // Ensure temp directory exists
            $tempDir = storage_path('app/temp');
            if (!is_dir($tempDir)) {
                mkdir($tempDir, 0755, true);
            }
            
            // Save PDF
            $pdf->save($path);
            
            return $path;

        } catch (\Exception $e) {
            Log::error('Failed to generate PDF receipt', [
                'payment_id' => $payment->id,
                'error' => $e->getMessage()
            ]);
            
            throw new \Exception('Impossible de générer le PDF: ' . $e->getMessage());
        }
    }

    /**
     * Check if email should be sent based on settings
     */
    public function shouldSendEmail(Payment $payment, bool $sendEmailCheckbox = true): bool
    {
        // Don't send if checkbox is unchecked
        if (!$sendEmailCheckbox) {
            return false;
        }

        // Don't send if member has no email
        if (empty($payment->member->email)) {
            return false;
        }

        // Don't send if email was already sent for this payment
        if ($payment->email_sent_at) {
            return false;
        }

        return true;
    }

    /**
     * Update payment record with email status
     */
    public function updatePaymentEmailStatus(Payment $payment, array $emailResult): void
    {
        $payment->update([
            'email_sent_at' => $emailResult['success'] ? now() : null,
            'email_status' => $emailResult['success'] ? 'sent' : 'failed',
            'email_error' => $emailResult['success'] ? null : $emailResult['message']
        ]);
    }
}
