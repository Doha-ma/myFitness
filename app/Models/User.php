<?php 

  

namespace App\Models; 

  

use Illuminate\Database\Eloquent\Factories\HasFactory; 

use Illuminate\Foundation\Auth\User as Authenticatable; 

use Illuminate\Notifications\Notifiable; 

  

class User extends Authenticatable 

{ 

    use HasFactory, Notifiable; 

  

    protected $fillable = [ 

        'name', 

        'email', 

        'password', 

        'role', 

    ]; 

  

    protected $hidden = [ 

        'password', 

        'remember_token', 

    ]; 

  

    protected function casts(): array 

    { 

        return [ 

            'email_verified_at' => 'datetime', 

            'password' => 'hashed', 

        ]; 

    } 

  

    // Relations 

    public function classesAsCoach() 

    { 

        return $this->hasMany(ClassModel::class, 'coach_id'); 

    } 

  

    public function paymentsAsReceptionist() 

    { 

        return $this->hasMany(Payment::class, 'receptionist_id'); 

    } 

  

    // Scopes 

    public function scopeAdmins($query) 

    { 

        return $query->where('role', 'admin'); 

    } 

  

    public function scopeReceptionists($query) 

    { 

        return $query->where('role', 'receptionist'); 

    } 

  

    public function scopeCoaches($query) 

    { 

        return $query->where('role', 'coach'); 

    } 

} 

  