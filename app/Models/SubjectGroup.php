<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class SubjectGroup extends Model
{
    protected $fillable = ['name'];

    public function academyClass(): BelongsTo
    {
        return $this->belongsTo(AcademyClass::class);
    }

    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'subject_group_subject');
    }
}