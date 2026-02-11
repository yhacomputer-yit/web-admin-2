<?php

namespace App\Models;

use App\Models\Course;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class course_type extends Model
{
    use HasFactory;

    protected $fillable = [
        "name"
    ]; 
    public function courses()
    {
        return $this->hasMany(Course::class, 'type', 'id');
    }

    

}
