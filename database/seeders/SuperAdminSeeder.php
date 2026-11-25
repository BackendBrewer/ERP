<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        $credentials = config('academy.super_admin');
        $user = User::firstOrNew(['email' => $credentials['email']]);
        $user->name = $credentials['name'];
        $user->password = $credentials['password'];
        $user->is_super_admin = true;
        $user->save();
    }
}
