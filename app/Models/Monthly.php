<?php

namespace App\Models;

use App\Models\Course;
use App\Models\Section;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Monthly extends Model
{
    use HasFactory;

    protected $fillable = [ 
        'course_id',
        'section_id',
        'start_date',
        'end_date', 
        'limited_seat',
        'm_img',
        'm_desc'  
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    } 
}
 