<?php

namespace App\Console\Commands;

use App\Mail\SubscriptionReminderEmail;
use App\Models\Payment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendSubscriptionReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscription:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send subscription expiration reminder emails to members';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Check if email reminders are enabled
        if (!config('subscription.email_reminders_enabled', true)) {
            $this->info('Email reminders are disabled. Skipping...');
            return 0;
        }

        $this->info('Sending subscription reminder emails...');

        $remindersSent = 0;
        $errors = 0;

        // Get members with active subscriptions
        $membersWithSubscriptions = Payment::with(['member', 'subscriptionType'])
            ->whereHas('member', function ($query) {
                $query->where('status', 'active');
            })
            ->whereHas('subscriptionType', function ($query) {
                $query->where('is_active', true);
            })
            ->whereNotNull('subscription_type_id')
            ->orderBy('payment_date', 'desc')
            ->get()
            ->groupBy('member_id');

        foreach ($membersWithSubscriptions as $memberId => $payments) {
            $member = $payments->first()->member;
            $lastPayment = $payments->first();
            $subscriptionType = $lastPayment->subscriptionType;

            if (!$member || !$subscriptionType) {
                continue;
            }

            // Calculate expiry date
            $expiryDate = $lastPayment->payment_date->copy()->addDays($subscriptionType->duration_days);
            $daysUntilExpiry = now()->diffInDays($expiryDate, false);

            // Send reminder if subscription expires within configured reminder periods
            $reminderDaysBefore = config('subscription.reminder_days_before', [7, 3, 1]);
            $reminderDaysAfter = config('subscription.reminder_days_after', [1, 3, 7]);
            
            if ($daysUntilExpiry <= 0 && in_array(abs($daysUntilExpiry), $reminderDaysAfter, true)) {
                // Recently expired (1, 3, or 7 days ago)
                $this->sendReminder($member, $lastPayment, $subscriptionType, $daysUntilExpiry, $remindersSent, $errors);
            } elseif (in_array($daysUntilExpiry, $reminderDaysBefore, true)) {
                // About to expire (7, 3, or 1 days before)
                $this->sendReminder($member, $lastPayment, $subscriptionType, $daysUntilExpiry, $remindersSent, $errors);
            }
        }

        $this->info("Process completed. Reminders sent: {$remindersSent}, Errors: {$errors}");
        
        return $errors === 0 ? 0 : 1;
    }

    private function sendReminder($member, $lastPayment, $subscriptionType, $daysUntilExpiry, &$remindersSent, &$errors)
    {
        try {
            // Check if member has a valid email
            if (empty($member->email)) {
                $this->warn("Member {$member->full_name} has no email address");
                return;
            }

            $mail = new SubscriptionReminderEmail($member, $lastPayment, $subscriptionType, $daysUntilExpiry);
            $mailer = Mail::to($member->email);

            if (config('subscription.queue_emails', true)) {
                $mailer->queue($mail);
            } else {
                $mailer->send($mail);
            }

            $remindersSent++;
            
            $status = $daysUntilExpiry <= 0 ? "expired" : "expires in {$daysUntilExpiry} days";
            $this->line("✓ Reminder sent to {$member->full_name} ({$member->email}) - Subscription {$status}");

        } catch (\Exception $e) {
            $errors++;
            $this->error("✗ Failed to send reminder to {$member->full_name}: {$e->getMessage()}");
            Log::error("Subscription reminder failed", [
                'member_id' => $member->id,
                'error' => $e->getMessage()
            ]);
        }
    }
}
