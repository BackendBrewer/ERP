@extends('layouts.app')

@section('title', 'Add Institute Admin')

@section('content')
    <a class="back-link" href="{{ route('super-admin.academies.index') }}"><x-icon name="arrow-left" /> Academies</a>
    <x-page-header
        title="Add Institute Admin"
        description="Create a staff account for {{ $academy->name }}."
        eyebrow="Workspace access"
        :breadcrumbs="[['label' => 'Workspace', 'url' => route('super-admin.dashboard')], ['label' => 'Academies', 'url' => route('super-admin.academies.index')], ['label' => 'Add Institute Admin']]"
    />

    <x-card title="Staff account" subtitle="The Institute Admin will use these details to access the academy workspace." class="form-card">
        <form class="form-stack" method="POST" action="{{ route('super-admin.academies.institute-admins.store', $academy) }}" data-loading-form>
            @csrf
            <div class="form-grid">
                <div class="field">
                    <label for="name">Full name<span class="required-mark" aria-hidden="true">*</span></label>
                    <input id="name" name="name" value="{{ old('name') }}" maxlength="255" placeholder="e.g. Ayesha Khan" required
                           @error('name') aria-invalid="true" aria-describedby="name-error" @enderror>
                    @error('name')
                        <p class="field-error" id="name-error" role="alert">{{ $message }}</p>
                    @enderror
                </div>

                <div class="field">
                    <label for="email">Email address<span class="required-mark" aria-hidden="true">*</span></label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" maxlength="255" placeholder="name@academy.edu" required
                           @error('email') aria-invalid="true" aria-describedby="email-error" @enderror>
                    @error('email')
                        <p class="field-error" id="email-error" role="alert">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="form-grid">
                <div class="field">
                    <label for="password">Initial password<span class="required-mark" aria-hidden="true">*</span></label>
                    <div class="password-field">
                        <input id="password" name="password" type="password" autocomplete="new-password"
                               minlength="12" placeholder="At least 12 characters" required aria-describedby="password-help{{ $errors->has('password') ? ' password-error' : '' }}"
                               @error('password') aria-invalid="true" @enderror>
                        <button class="password-toggle" type="button" data-password-toggle="password"
                                aria-controls="password" hidden>Show password</button>
                    </div>
                    <p class="help" id="password-help">Use at least 12 characters.</p>
                    @error('password')
                        <p class="field-error" id="password-error" role="alert">{{ $message }}</p>
                    @enderror
                </div>

                <div class="field">
                    <label for="password_confirmation">Confirm password<span class="required-mark" aria-hidden="true">*</span></label>
                    <input id="password_confirmation" name="password_confirmation"
                           type="password" autocomplete="new-password" minlength="12" placeholder="Enter the password again" required>
                </div>
            </div>

            <div class="form-actions">
                <button class="button button-primary" type="submit">Create Institute Admin <x-icon name="arrow-right" /></button>
                <a class="button button-secondary" href="{{ route('super-admin.academies.index') }}">Cancel</a>
            </div>
        </form>
    </x-card>
@endsection
