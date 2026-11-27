<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    protected function casts(): array
    {
        return ['admitted_on' => 'date'];
    }
}