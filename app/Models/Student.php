<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    protected $fillable = ['name', 'guardian_name', 'contact_phone'];

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }
}