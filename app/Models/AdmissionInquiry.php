<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class AdmissionInquiry extends Model
{
    protected $fillable = [
        'academy_class_id',
        'student_name',
        'guardian_name',
        'contact_phone',
        'follow_up_on',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'follow_up_on' => 'date',
        ];
    }

    public function academyClass(): BelongsTo
    {
        return $this->belongsTo(AcademyClass::class);
    }

    public function enrollment(): HasOne
    {
        return $this->hasOne(Enrollment::class);
    }
}