<?php

namespace App\Console\Commands;

use App\Models\Member;
use App\Models\User;
use App\Notifications\ExpiredSubscriptionsNotification;
use Illuminate\Console\Command;

class CheckSubscriptionExpiry extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:check-expiry';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Met a jour les statuts des abonnements et notifie les administrateurs';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Verification des abonnements en cours...');

        $members = Member::with('latestSubscriptionPayment.subscriptionType')->get();
        $expiredCount = 0;

        foreach ($members as $member) {
            if (!$member->subscription_end_date) {
                $payment = $member->latestSubscriptionPayment;

                if ($payment && $payment->subscriptionType) {
                    $member->subscription_end_date = $payment->payment_date
                        ->copy()
                        ->addDays((int) $payment->subscriptionType->duration_days);
                }
            }

            if ($member->subscription_end_date) {
                $member->status = $member->subscription_end_date->lt(today()) ? 'inactive' : 'active';

                if ($member->status === 'inactive') {
                    $expiredCount++;
                }
            }

            $member->save();
        }

        if ($expiredCount > 0) {
            $this->notifyAdmins($expiredCount);
        }

        $this->info("Verification terminee. Membres expires : {$expiredCount}.");

        return self::SUCCESS;
    }

    private function notifyAdmins(int $expiredCount): void
    {
        $today = now()->toDateString();
        $admins = User::where('role', 'admin')->get();

        foreach ($admins as $admin) {
            $alreadyNotified = $admin->unreadNotifications->contains(function ($notification) use ($today, $expiredCount) {
                return ($notification->data['action_type'] ?? null) === 'expired_subscriptions'
                    && ($notification->data['date'] ?? null) === $today
                    && (int) ($notification->data['expired_count'] ?? 0) === $expiredCount;
            });

            if (!$alreadyNotified) {
                $admin->notify(new ExpiredSubscriptionsNotification($expiredCount));
            }
        }
    }
}

