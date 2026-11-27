@extends('layouts.app')

@section('title', 'Create academy')

@section('content')
    <a class="back-link" href="{{ route('super-admin.academies.index') }}"><x-icon name="arrow-left" /> Academies</a>
    <x-page-header
        title="Create academy"
        description="Add the name and unique slug for a new academy workspace."
        eyebrow="Platform management"
        :breadcrumbs="[['label' => 'Workspace', 'url' => route('super-admin.dashboard')], ['label' => 'Academies', 'url' => route('super-admin.academies.index')], ['label' => 'Create academy']]"
    />

    <x-card title="Academy details" subtitle="A few details are all you need to create a new workspace." class="form-card">
        <form class="form-stack" method="POST" action="{{ route('super-admin.academies.store') }}" data-loading-form>
            @csrf
            <div class="field">
                <label for="name">Academy name<span class="required-mark" aria-hidden="true">*</span></label>
                <input id="name" name="name" value="{{ old('name') }}" maxlength="150" placeholder="e.g. Northview Academy" required
                       @error('name') aria-invalid="true" aria-describedby="name-error" @enderror>
                @error('name')
                    <p class="field-error" id="name-error" role="alert">{{ $message }}</p>
                @enderror
            </div>

            <div class="field">
                <label for="slug">Workspace slug<span class="required-mark" aria-hidden="true">*</span></label>
                <input id="slug" name="slug" value="{{ old('slug') }}" maxlength="100"
                       autocapitalize="none" spellcheck="false" placeholder="e.g. northview-academy" required
                       aria-describedby="slug-help{{ $errors->has('slug') ? ' slug-error' : '' }}"
                       @error('slug') aria-invalid="true" @enderror>
                <p class="help" id="slug-help">Use lowercase letters, numbers and single hyphens, like <code>lahore-evening</code>.</p>
                @error('slug')
                    <p class="field-error" id="slug-error" role="alert">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-actions">
                <button class="button button-primary" type="submit">Create academy <x-icon name="arrow-right" /></button>
                <a class="button button-secondary" href="{{ route('super-admin.academies.index') }}">Cancel</a>
            </div>
        </form>
    </x-card>
@endsection
