<?php

namespace App\Models;

use App\Models\EventDetail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'edate',
        'aboute',
        'image'

    ];

    public function details(){
        return $this->hasOne(EventDetail::class);
    }
}
