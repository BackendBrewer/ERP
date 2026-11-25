<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InstituteRole extends Model
{
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(
            Permission::class,
            'institute_role_permission',
            'institute_role_id',
            'permission_id'
        )->withTimestamps();
    }
    public function institute(): BelongsTo
    {
        return $this->belongsTo(Institute::class);
    }
}
