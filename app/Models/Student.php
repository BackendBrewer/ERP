<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Student extends Model
{
    protected $fillable = [
        'name', 'guardian_name', 'contact_phone', 'student_phone', 'guardian_phone',
        'whatsapp_phone', 'admission_number', 'date_of_birth', 'gender', 'address', 'email',
    ];

    protected function casts(): array
    {
        return ['date_of_birth' => 'date'];
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function latestEnrollment(): HasOne
    {
        return $this->hasOne(Enrollment::class)
            ->ofMany(['admitted_on' => 'max', 'id' => 'max']);
    }
}