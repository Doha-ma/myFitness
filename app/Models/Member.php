<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'join_date',
        'subscription_end_date',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'join_date' => 'date',
            'subscription_end_date' => 'date',
        ];
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function latestPayment()
    {
        return $this->hasOne(Payment::class)->ofMany('payment_date', 'max');
    }

    public function latestSubscriptionPayment()
    {
        return $this->hasOne(Payment::class)
            ->whereNotNull('subscription_type_id')
            ->ofMany('payment_date', 'max');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function classes()
    {
        return $this->belongsToMany(ClassModel::class, 'enrollments', 'member_id', 'class_id')
            ->withPivot('enrollment_date')
            ->withTimestamps();
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getResolvedSubscriptionEndDateAttribute(): ?Carbon
    {
        if ($this->subscription_end_date) {
            return Carbon::parse($this->subscription_end_date)->startOfDay();
        }

        $payment = $this->relationLoaded('latestSubscriptionPayment')
            ? $this->latestSubscriptionPayment
            : $this->latestSubscriptionPayment()->with('subscriptionType')->first();

        if (!$payment || !$payment->subscriptionType) {
            return null;
        }

        return $payment->payment_date
            ->copy()
            ->startOfDay()
            ->addDays((int) $payment->subscriptionType->duration_days);
    }

    public function getSubscriptionStateAttribute(): string
    {
        $endDate = $this->resolved_subscription_end_date;

        if (!$endDate) {
            return 'expired';
        }

        return $endDate->lt(now()->startOfDay()) ? 'expired' : 'active';
    }

    public function syncMembershipStatusFromSubscription(): void
    {
        $this->status = $this->subscription_state === 'active' ? 'active' : 'inactive';
    }
}

