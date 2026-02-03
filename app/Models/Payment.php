<?php 

  

namespace App\Models; 

  

use Illuminate\Database\Eloquent\Factories\HasFactory; 

use Illuminate\Database\Eloquent\Model; 

  

class Payment extends Model 

{ 

    use HasFactory; 

  

    protected $fillable = [ 

        'member_id', 

        'receptionist_id', 

        'subscription_type_id',

        'amount', 

        'payment_date', 

        'method', 

        'notes', 

    ]; 

  

    protected function casts(): array 

    { 

        return [ 

            'payment_date' => 'date', 

            'amount' => 'decimal:2', 

        ]; 

    } 

  

    public function member() 

    { 

        return $this->belongsTo(Member::class); 

    } 

  

    public function receptionist() 

    { 

        return $this->belongsTo(User::class, 'receptionist_id'); 

    }

    public function subscriptionType()
    {
        return $this->belongsTo(SubscriptionType::class);
    }

} 

  