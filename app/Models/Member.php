<?php 

  

namespace App\Models; 

  

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

        'status', 

    ]; 

  

    protected function casts(): array 

    { 

        return [ 

            'join_date' => 'date', 

        ]; 

    } 

  

    public function payments() 

    { 

        return $this->hasMany(Payment::class); 

    } 

  

    public function enrollments() 

    { 

        return $this->hasMany(Enrollment::class); 

    } 

  

    public function classes() 

    { 

        return $this->belongsToMany(ClassModel::class, 'enrollments') 

            ->withPivot('enrollment_date') 

            ->withTimestamps(); 

    } 

  

    public function getFullNameAttribute() 

    { 

        return "{$this->first_name} {$this->last_name}"; 

    } 

} 