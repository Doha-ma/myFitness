<?php 



  



namespace App\Models; 



  



use Illuminate\Database\Eloquent\Factories\HasFactory; 



use Illuminate\Database\Eloquent\Model; 



  



class ClassModel extends Model 



{ 



    use HasFactory; 



  



    protected $table = 'classes'; 



  



    protected $fillable = [ 



        'name', 



        'description', 



        'coach_id', 



        'capacity', 



        'duration', 



        'status',

        'rejection_reason',

    ]; 



  



    public function coach() 



    { 



        return $this->belongsTo(User::class, 'coach_id'); 



    } 



  



    public function schedules() 



    { 



        return $this->hasMany(Schedule::class, 'class_id'); 



    } 



  



    public function enrollments() 



    { 



        return $this->hasMany(Enrollment::class, 'class_id'); 



    } 



  



    public function members() 



    { 



        return $this->belongsToMany(

            Member::class,

            'enrollments',

            'class_id',

            'member_id')

            ->withPivot('enrollment_date') 



            ->withTimestamps(); 



    } 



  



    public function getEnrollmentCountAttribute() 



    { 



        return $this->enrollments()->count(); 



    } 



} 



  