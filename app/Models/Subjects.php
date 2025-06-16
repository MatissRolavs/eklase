<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subjects extends Model
{
    protected $fillable = [
        'name',
    ];

    public function grades()
    {
        return $this->hasMany(Grades2::class, 'subject_id');
    }
}
