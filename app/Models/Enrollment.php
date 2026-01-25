<?php 

  

namespace App\Models; 

  

use Illuminate\Database\Eloquent\Factories\HasFactory; 

use Illuminate\Database\Eloquent\Model; 

  

class Enrollment extends Model 

{ 

    use HasFactory; 

  

    protected $fillable = [ 

        'member_id', 

        'class_id', 

        'enrollment_date', 

    ]; 

  

    protected function casts(): array 

    { 

        return [ 

            'enrollment_date' => 'date', 

        ]; 

    } 

  

    public function member() 

    { 

        return $this->belongsTo(Member::class); 

    } 

  

    public function classModel() 

    { 

        return $this->belongsTo(ClassModel::class, 'class_id'); 

    } 

} 