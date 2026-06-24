<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    protected $fillable = [
        'student_id',
        'file_path',
        'file_name',
        'note',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
