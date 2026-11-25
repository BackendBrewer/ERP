<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_super_admin' => 'boolean',
        ];
    }

    public function institutes(): BelongsToMany
    {
        return $this->belongsToMany(Institute::class)
            ->withPivot('status', 'role_id')
            ->withTimestamps();
    }

    public function hasInstitutePermission(string $permissionSlug, ?Institute $institute = null): bool
    {
        if ($this->is_super_admin) {
            return false;
        }

        $institute ??= request()->attributes->get('currentInstitute');

        if (! $institute instanceof Institute) {
            return false;
        }

        $membership = $this->institutes()
            ->where('institutes.id', $institute->id)
            ->where('institutes.status', 'active')
            ->wherePivot('status', 'active')
            ->first();

        if ($membership === null || $membership->pivot->role_id === null) {
            return false;
        }

        return InstituteRole::query()
            ->whereKey($membership->pivot->role_id)
            ->where('institute_id', $institute->id)
            ->whereHas('permissions', fn ($query) => $query->where('permissions.slug', $permissionSlug))
            ->exists();
    }

    public function instituteRole(?Institute $institute = null): ?InstituteRole
    {
        if ($this->is_super_admin) {
            return null;
        }

        $institute ??= request()->attributes->get('currentInstitute');

        if (! $institute instanceof Institute) {
            return null;
        }

        $membership = $this->institutes()
            ->where('institutes.id', $institute->id)
            ->where('institutes.status', 'active')
            ->wherePivot('status', 'active')
            ->first();

        if ($membership === null || $membership->pivot->role_id === null) {
            return null;
        }

        return InstituteRole::query()
            ->whereKey($membership->pivot->role_id)
            ->where('institute_id', $institute->id)
            ->first();
    }
}
