<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InstituteProfile extends Model
{
    public function institute(): BelongsTo
    {
        return $this->belongsTo(Institute::class);
    }
}
