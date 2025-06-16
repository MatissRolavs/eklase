<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grades extends Model
{
    protected $fillable = [
        'student_id',
        'subject_id',
        'grade',
    ];

    public function student()
    {
        return $this->belongsTo(Students::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subjects::class);
    }
}
