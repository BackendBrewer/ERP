<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Institute extends Model
{
    public function profile(): HasOne
    {
        return $this->hasOne(InstituteProfile::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot('status', 'role_id')
            ->withTimestamps();
    }

    public function roles(): HasMany
    {
        return $this->hasMany(InstituteRole::class);
    }
}
