@extends('layouts.app')

@section('title', 'Subject Groups')

@section('content')
    <x-page-header
        title="Subject Groups"
        description="Organize subjects into class-specific groups for your academy."
        eyebrow="Academic setup"
        :breadcrumbs="[['label' => 'Workspace', 'url' => route('institute.dashboard')], ['label' => 'Subject Groups']]"
    />
    @include('partials.status')

    <div class="management-grid">
        <x-card title="Subject group directory" subtitle="Each group is connected to a class and its selected subjects." class="directory">
            <div class="table-toolbar">
                <span class="muted">{{ $groups->count() }} shown on this page</span>
                @if ($groups->count())
                    <label class="table-search">
                        <x-icon name="search" />
                        <input type="search" data-table-search="subject-groups-table" placeholder="Search groups…" aria-label="Search subject groups">
                    </label>
                @endif
            </div>

            @if ($groups->count())
                <div class="table-wrap">
                    <table class="directory-table" id="subject-groups-table" data-global-searchable>
                        <caption class="sr-only">Subject groups, classes and included subjects</caption>
                        <thead>
                            <tr>
                                <th scope="col">Group name</th>
                                <th scope="col">Class</th>
                                <th scope="col">Subjects</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($groups as $group)
                                <tr data-searchable>
                                    <th scope="row">{{ $group->name }}</th>
                                    <td>{{ $group->academyClass->name }}</td>
                                    <td>
                                        <ul class="section-tags" aria-label="Subjects in {{ $group->name }}">
                                            @forelse ($group->subjects as $subject)
                                                <li class="tag">{{ $subject->name }}</li>
                                            @empty
                                                <li class="muted">No subjects assigned</li>
                                            @endforelse
                                        </ul>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="search-empty" data-search-empty hidden>No subject groups match your search.</div>
                </div>
            @else
                <x-empty-state
                    :title="$groups->currentPage() > 1 ? 'No groups on this page' : 'No subject groups yet'"
                    :description="$groups->currentPage() > 1 ? null : 'Create a group to connect a class with the subjects it studies.'"
                    icon="layers"
                />
            @endif

            @include('partials.pagination', ['page' => $groups, 'label' => 'Subject group pages'])
        </x-card>

        <x-card title="Create subject group" subtitle="Choose a class and at least one subject." class="form-card">
            <form class="form-stack" method="POST" action="{{ route('institute.subject-groups.store') }}" data-loading-form>
                @csrf
                <div class="field">
                    <label for="name">Group name<span class="required-mark" aria-hidden="true">*</span></label>
                    <input id="name" name="name" value="{{ old('name') }}" maxlength="100" placeholder="e.g. Core sciences" required
                           @error('name') aria-invalid="true" aria-describedby="name-error" @enderror>
                    @error('name')
                        <p class="field-error" id="name-error" role="alert">{{ $message }}</p>
                    @enderror
                </div>

                <div class="field">
                    <label for="academy_class_id">Class<span class="required-mark" aria-hidden="true">*</span></label>
                    <select id="academy_class_id" name="academy_class_id" required
                            @error('academy_class_id') aria-invalid="true" aria-describedby="class-error" @enderror>
                        <option value="">Select a class</option>
                        @foreach ($classOptions as $class)
                            <option value="{{ $class->id }}" @selected((string) old('academy_class_id') === (string) $class->id)>{{ $class->name }}</option>
                        @endforeach
                    </select>
                    @error('academy_class_id')
                        <p class="field-error" id="class-error" role="alert">{{ $message }}</p>
                    @enderror
                </div>

                @php($selectedSubjectIds = collect(old('subject_ids', []))->map(fn ($id) => (string) $id)->all())
                <fieldset class="subject-fieldset" aria-describedby="subject-ids-help{{ $errors->has('subject_ids') ? ' subject-ids-error' : '' }}">
                    <legend class="field-legend">Subjects<span class="required-mark" aria-hidden="true">*</span></legend>
                    <p class="help" id="subject-ids-help">Select one or more subjects for this group.</p>
                    <div class="subject-picker" data-subject-picker>
                        @if ($subjectOptions->isNotEmpty())
                            <label class="subject-option-filter">
                                <x-icon name="search" />
                                <input type="search" data-subject-filter placeholder="Find a subject…" aria-label="Search available subjects" autocomplete="off">
                            </label>
                            <div class="subject-picker-meta"><span>Available subjects</span><span data-subject-count>0 selected</span></div>
                            <div class="subject-option-list">
                                @foreach ($subjectOptions as $subject)
                                    <label class="subject-option" data-subject-option>
                                        <input type="checkbox" name="subject_ids[]" value="{{ $subject->id }}"
                                               @checked(in_array((string) $subject->id, $selectedSubjectIds, true))>
                                        <span>{{ $subject->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                            <p class="subject-option-empty" data-subject-empty hidden>No subjects match your search.</p>
                        @else
                            <p class="help">Add subjects to your academy before creating a group.</p>
                        @endif
                    </div>
                    @error('subject_ids')
                        <p class="field-error" id="subject-ids-error" role="alert">{{ $message }}</p>
                    @enderror
                </fieldset>

                @if ($classOptions->isNotEmpty() && $subjectOptions->isNotEmpty())
                    <button class="button button-primary" type="submit">Create subject group <x-icon name="plus" /></button>
                @else
                    <div class="empty-state setup-hint">
                        <p>Before creating a group, add at least one class and one subject.</p>
                        <div class="page-heading-actions">
                            <a class="button button-secondary" href="{{ route('institute.academic-setup.index') }}">Classes &amp; Sections</a>
                            <a class="button button-secondary" href="{{ route('institute.subjects.index') }}">Subjects</a>
                        </div>
                    </div>
                @endif
            </form>
        </x-card>
    </div>
@endsection
