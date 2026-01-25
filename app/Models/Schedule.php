<?php 

  

namespace App\Models; 

  

use Illuminate\Database\Eloquent\Factories\HasFactory; 

use Illuminate\Database\Eloquent\Model; 

  

class Schedule extends Model 

{ 

    use HasFactory; 

  

    protected $fillable = [ 

        'class_id', 

        'day_of_week', 

        'start_time', 

        'end_time', 

    ]; 

  

    protected function casts(): array 

    { 

        return [ 

            'start_time' => 'datetime:H:i', 

            'end_time' => 'datetime:H:i', 

        ]; 

    } 

  

    public function classModel() 

    { 

        return $this->belongsTo(ClassModel::class, 'class_id'); 

    } 

} 