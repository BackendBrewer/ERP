<?php

namespace App\Console\Commands;

use App\Models\Institute;
use App\Models\InstituteProfile;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CreateInstitute extends Command
{
    protected $signature = 'erp:create-institute';

    protected $description = 'Create an institute and its initial staff account';

    public function handle(): int
    {
        if (! app()->environment('local')) {
            $this->error('This setup command is allowed only in the local environment.');

            return self::FAILURE;
        }

        $name = trim($this->ask('Institute name'));
        $slug = Str::slug($name);

        $userName = trim($this->ask('Staff member name'));
        $email = strtolower(trim($this->ask('Staff email')));

        $password = $this->secret('Password (minimum 12 characters)');
        $confirmation = $this->secret('Confirm password');

        if ($password !== $confirmation) {
            $this->error('Passwords do not match.');

            return self::FAILURE;
        }

        $validator = Validator::make([
            'name' => $name,
            'slug' => $slug,
            'user_name' => $userName,
            'email' => $email,
            'password' => $password,
        ], [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:institutes,slug'],
            'user_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:12'],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return self::FAILURE;
        }

        DB::transaction(function () use ($name, $slug, $userName, $email, $password) {
            $institute = new Institute();
            $institute->name = $name;
            $institute->slug = $slug;
            $institute->status = 'active';
            $institute->save();

            $profile = new InstituteProfile();
            $profile->institute_id = $institute->id;
            $profile->save();

            $user = new User();
            $user->name = $userName;
            $user->email = $email;
            $user->password = Hash::make($password);
            $user->save();

            $institute->users()->attach($user->id, [
                'status' => 'active',
            ]);
        });

        $this->info('Institute and staff account created successfully.');
        $this->line('Institute slug: '.$slug);
        $this->line('Staff email: '.$email);
        $this->warn('Roles and permissions are not assigned yet.');

        return self::SUCCESS;
    }
}
