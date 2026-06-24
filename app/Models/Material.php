<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $fillable = [
        'student_id',
        'type',
        'file_path',
        'file_name',
        'week_date',
        'note',
        'uploaded_by',
    ];

    protected $casts = [
        'week_date' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
