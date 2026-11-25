<?php

namespace App\Http\Controllers;

use App\Models\InstituteProfile;
use App\Models\Institute;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class InstituteOnboardingController extends Controller
{
    public function create(Request $request): View|RedirectResponse
    {
        if (! $request->user()?->is_super_admin && $request->user()?->institutes()->exists()) {
            return redirect()->route('institutes.select');
        }

        return view('institutes.onboarding');
    }

    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();
        $isPlatformAdmin = (bool) $user?->is_super_admin;

        if ($user && ! $isPlatformAdmin && $user->institutes()->exists()) {
            return redirect()->route('institutes.select');
        }

        $validated = $request->validate([
            'institute_name' => ['required', 'string', 'max:255'],
            'name' => [$user && ! $isPlatformAdmin ? 'nullable' : 'required', 'string', 'max:255'],
            'email' => [$user && ! $isPlatformAdmin ? 'nullable' : 'required', 'email', 'max:255', Rule::unique('users', 'email')],
            'password' => [$user && ! $isPlatformAdmin ? 'nullable' : 'required', 'string', 'min:8', 'confirmed'],
        ]);

        [$owner, $institute] = DB::transaction(function () use ($validated, $user, $isPlatformAdmin): array {
            $owner = $user && ! $isPlatformAdmin ? $user : User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => $validated['password'],
            ]);

            $baseSlug = Str::slug($validated['institute_name']) ?: 'institute';
            $slug = $baseSlug;
            $suffix = 2;

            while (Institute::where('slug', $slug)->exists()) {
                $slug = $baseSlug.'-'.$suffix++;
            }

            $institute = new Institute;
            $institute->name = $validated['institute_name'];
            $institute->slug = $slug;
            $institute->status = 'active';
            $institute->save();

            $institute->profile()->save(new InstituteProfile);
            $owner->institutes()->attach($institute->id, ['status' => 'active']);

            return [$owner, $institute];
        });

        if (! $request->user()) {
            Auth::login($owner);
        }

        if ($isPlatformAdmin) {
            return redirect()->route('platform.dashboard')->with('status', 'Institute and institute administrator created.');
        }

        $request->session()->regenerate();
        $request->session()->put('current_institute_id', $institute->id);

        return redirect()->route('workspace');
    }
}
