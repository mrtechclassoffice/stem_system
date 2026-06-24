<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'student_id',
        'amount',
        'status',
        'period_month',
        'paid_on',
        'note',
    ];

    protected $casts = [
        'paid_on' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
