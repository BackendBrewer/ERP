@extends('layouts.app')

@section('title', 'Subjects')

@section('content')
    <x-page-header title="Subjects" description="Maintain the subjects offered by your academy." eyebrow="Academic setup"
        :breadcrumbs="[['label' => 'Workspace', 'url' => route('institute.dashboard')], ['label' => 'Subjects']]" />
    @include('partials.status')

    <div class="management-grid">
        <x-card title="Subject catalog" subtitle="Your academy’s current subject list." class="directory">
            <div class="table-toolbar">
                <span class="muted">{{ $subjects->count() }} shown on this page</span>
                <label class="table-search"><x-icon name="search" /><input type="search" data-table-search="subjects-table" placeholder="Search subjects…" aria-label="Search subjects"></label>
            </div>
            <div class="table-wrap">
                <table class="directory-table" id="subjects-table" data-global-searchable>
                    <caption class="sr-only">Subject catalog</caption>
                    <thead><tr><th scope="col">Subject name</th></tr></thead>
                    <tbody>
                        @forelse ($subjects as $subject)
                            <tr data-searchable><th scope="row">{{ $subject->name }}</th></tr>
                        @empty
                            <tr><td><x-empty-state title="No subjects yet" :description="$subjects->currentPage() > 1 ? 'There are no subjects on this page.' : 'Add your first subject to build the academy catalog.'" icon="book" /></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="search-empty" data-search-empty hidden>No subjects match your search.</div>
            @include('partials.pagination', ['page' => $subjects, 'label' => 'Subject pages'])
        </x-card>

        <x-card title="Add subject" subtitle="Add a subject to your academy catalog." class="form-card">
            <form class="form-stack" method="POST" action="{{ route('institute.subjects.store') }}" data-loading-form>
                @csrf
                <div class="field">
                    <label for="name">Subject name<span class="required-mark" aria-hidden="true">*</span></label>
                    <input id="name" name="name" value="{{ old('name') }}" maxlength="100" placeholder="e.g. Mathematics" required
                           @error('name') aria-invalid="true" aria-describedby="name-error" @enderror>
                    @error('name')
                        <p class="field-error" id="name-error" role="alert">{{ $message }}</p>
                    @enderror
                </div>
                <button class="button button-primary" type="submit">Add subject <x-icon name="plus" /></button>
            </form>
        </x-card>
    </div>
@endsection
