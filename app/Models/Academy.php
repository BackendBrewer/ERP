<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Academy extends Model
{
    protected $fillable = [
        'name',
        'slug',
    ];

    public function classes(): HasMany
    {
        return $this->hasMany(AcademyClass::class);
    }

    public function subjects(): HasMany
    {
        return $this->hasMany(Subject::class);
    }

    public function admissionInquiries(): HasMany
    {
        return $this->hasMany(AdmissionInquiry::class);
    }

    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }
}
