<?php

namespace App\Console\Commands;

use App\Mail\SubscriptionReminderEmail;
use App\Models\Member;
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
    protected $description = 'Envoie les emails de rappel pour les abonnements expires';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        if (!config('subscription.email_reminders_enabled', true)) {
            $this->info('Les rappels emails sont desactives.');

            return self::SUCCESS;
        }

        $this->info('Envoi des rappels d abonnement expire...');

        $remindersSent = 0;
        $errors = 0;
        $reminderDaysAfter = config('subscription.reminder_days_after', [1, 3, 7]);

        $members = Member::with('latestSubscriptionPayment.subscriptionType')
            ->whereNotNull('email')
            ->whereNotNull('subscription_end_date')
            ->get();

        foreach ($members as $member) {
            $payment = $member->latestSubscriptionPayment;
            $subscriptionType = $payment?->subscriptionType;

            if (!$payment || !$subscriptionType) {
                continue;
            }

            $expiryDate = $member->subscription_end_date->copy()->startOfDay();
            $daysUntilExpiry = now()->startOfDay()->diffInDays($expiryDate, false);

            if ($daysUntilExpiry > 0) {
                continue;
            }

            $daysAfterExpiry = abs($daysUntilExpiry);
            if (!in_array($daysAfterExpiry, $reminderDaysAfter, true)) {
                continue;
            }

            $this->sendReminder($member, $payment, $subscriptionType, $daysUntilExpiry, $remindersSent, $errors);
        }

        $this->info("Termine. Emails envoyes : {$remindersSent}. Erreurs : {$errors}.");

        return $errors === 0 ? self::SUCCESS : self::FAILURE;
    }

    private function sendReminder($member, $lastPayment, $subscriptionType, int $daysUntilExpiry, int &$remindersSent, int &$errors): void
    {
        try {
            if (empty($member->email)) {
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
            $this->line("Rappel envoye a {$member->full_name} ({$member->email})");
        } catch (\Exception $e) {
            $errors++;

            $this->error("Echec d envoi pour {$member->full_name}: {$e->getMessage()}");
            Log::error('Subscription reminder failed', [
                'member_id' => $member->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}

