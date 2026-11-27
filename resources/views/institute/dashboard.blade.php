@extends('layouts.app')

@section('title', 'Institute overview')

@section('content')
    <section class="hero">
        <div class="hero-content">
            <span class="page-eyebrow">Institute workspace</span>
            <h1>{{ $academy->name }}</h1>
            <p>Build a well-organized academic foundation for your students and staff.</p>
            <div class="hero-actions">
                <a class="button button-amber" href="{{ route('institute.academic-setup.index') }}">Open academic setup <x-icon name="arrow-right" /></a>
                <a class="button button-secondary" href="{{ route('institute.subjects.index') }}">Browse subjects</a>
            </div>
        </div>
    </section>

    <div class="section-title-row">
        <div>
            <h2>Workspace essentials</h2>
            <p>Keep your academy structure ready for the year ahead.</p>
        </div>
    </div>

    <section class="dashboard-grid" aria-label="Institute actions">
        <a class="action-card" href="{{ route('institute.academic-setup.index') }}">
            <span class="tile-icon"><x-icon name="grid" /></span>
            <h2>Classes &amp; Sections</h2>
            <p>Create classes and organize each group into sections.</p>
            <span class="card-link">Open academic setup <x-icon name="arrow-right" /></span>
        </a>
        <a class="action-card accent-card" href="{{ route('institute.subjects.index') }}">
            <span class="tile-icon"><x-icon name="book" /></span>
            <h2>Subjects</h2>
            <p>Maintain the subjects offered across your academy.</p>
            <span class="card-link">Open subject catalog <x-icon name="arrow-right" /></span>
        </a>
        <a class="action-card" href="{{ route('institute.subject-groups.index') }}">
            <span class="tile-icon"><x-icon name="layers" /></span>
            <h2>Subject Groups</h2>
            <p>Connect classes with the subjects they study.</p>
            <span class="card-link">Open subject groups <x-icon name="arrow-right" /></span>
        </a>
    </section>
@endsection
