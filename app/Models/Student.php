<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'user_id',
        'full_name',
        'age',
        'birthday',
        'school',
        'address',
        'parent_name',
        'parent_nic',
        'parent_contact',
        'profile_picture_path',
    ];

    protected $casts = [
        'birthday' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function materials()
    {
        return $this->hasMany(Material::class);
    }

    public function mediaLinks()
    {
        return $this->hasMany(MediaLink::class);
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
