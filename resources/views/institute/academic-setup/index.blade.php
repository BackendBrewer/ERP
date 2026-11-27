@extends('layouts.app')

@section('title', 'Classes & Sections')

@section('content')
    <x-page-header title="Classes &amp; Sections" description="Organize the classes and sections taught at your academy." eyebrow="Academic setup"
        :breadcrumbs="[['label' => 'Workspace', 'url' => route('institute.dashboard')], ['label' => 'Classes & Sections']]" />
    @include('partials.status')

    <div class="management-grid">
        <x-card title="Class directory" subtitle="Classes and the sections within each one." class="directory">
            <div class="table-toolbar">
                <span class="muted">{{ $classes->count() }} shown on this page</span>
                <label class="table-search"><x-icon name="search" /><input type="search" data-list-search="class-list" placeholder="Search classes…" aria-label="Search classes"></label>
            </div>
            <ul class="record-list" id="class-list">
                @forelse ($classes as $class)
                    <li data-searchable>
                        <span class="tile-icon"><x-icon name="grid" /></span>
                        <div class="class-details">
                            <strong>{{ $class->name }}</strong>
                            @if ($class->sections->isNotEmpty())
                                <ul class="section-tags" aria-label="Sections">
                                    @foreach ($class->sections as $section)
                                        <li class="tag">{{ $section->name }}</li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="help">No sections yet.</p>
                            @endif
                        </div>
                    </li>
                @empty
                    <li><x-empty-state title="No classes yet" :description="$classes->currentPage() > 1 ? 'There are no classes on this page.' : 'Add a class to start organizing sections.'" icon="grid" /></li>
                @endforelse
            </ul>
            <div class="search-empty" data-search-empty hidden>No classes match your search.</div>
            @include('partials.pagination', ['page' => $classes, 'label' => 'Class pages'])
        </x-card>

        <div class="side-forms">
            <x-card title="Add class" subtitle="Create a class in your academy." class="form-card">
                <form class="form-stack" method="POST" action="{{ route('institute.academic-setup.classes.store') }}" data-loading-form>
                    @csrf
                    <div class="field">
                        <label for="class_name">Class name<span class="required-mark" aria-hidden="true">*</span></label>
                        <input id="class_name" name="class_name" value="{{ old('class_name') }}" maxlength="100" placeholder="e.g. Grade 7" required
                               @error('class_name') aria-invalid="true" aria-describedby="class-error" @enderror>
                        @error('class_name')
                            <p class="field-error" id="class-error" role="alert">{{ $message }}</p>
                        @enderror
                    </div>
                    <button class="button button-primary" type="submit">Save class <x-icon name="plus" /></button>
                </form>
            </x-card>

            @if ($classes->count())
                <x-card title="Add section" subtitle="Place a section within a class." class="form-card">
                    <form class="form-stack" method="POST" action="{{ route('institute.academic-setup.sections.store') }}" data-loading-form>
                        @csrf
                        <div class="field">
                            <label for="academy_class_id">Class on this page<span class="required-mark" aria-hidden="true">*</span></label>
                            <select id="academy_class_id" name="academy_class_id" required
                                    @error('academy_class_id') aria-invalid="true" aria-describedby="class-id-error" @enderror>
                                <option value="">Select a class</option>
                                @foreach ($classes as $class)
                                    <option value="{{ $class->id }}" @selected((string) old('academy_class_id') === (string) $class->id)>{{ $class->name }}</option>
                                @endforeach
                            </select>
                            @error('academy_class_id')
                                <p class="field-error" id="class-id-error" role="alert">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="field">
                            <label for="section_name">Section name<span class="required-mark" aria-hidden="true">*</span></label>
                            <input id="section_name" name="section_name" value="{{ old('section_name') }}" maxlength="50" placeholder="e.g. Section A" required
                                   @error('section_name') aria-invalid="true" aria-describedby="section-error" @enderror>
                            @error('section_name')
                                <p class="field-error" id="section-error" role="alert">{{ $message }}</p>
                            @enderror
                        </div>
                        <button class="button button-secondary" type="submit">Save section <x-icon name="plus" /></button>
                    </form>
                </x-card>
            @endif
        </div>
    </div>
@endsection
