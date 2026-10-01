<?php

namespace App\Models;

use App\Models\Course;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Section extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'start',
        'end',
    ];

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_sections', 'section_id', 'course_id');
    }

    public function monthlies()
    {
        return $this->hasMany(Monthly::class);
    }

    public function enrollments()
    {
        return $this->hasMany(StudentEnrollment::class, 'section_id');
    }
}
