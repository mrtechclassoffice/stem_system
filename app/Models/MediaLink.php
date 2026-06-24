<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MediaLink extends Model
{
    protected $fillable = [
        'student_id',
        'type',
        'drive_url',
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
