@extends('layouts.app')

@section('title', 'Institute overview')

@section('content')
    <section class="hero">
        <div class="hero-content">
            <span class="page-eyebrow">Institute workspace</span>
            <h1>{{ $academy->name }}</h1>
            <p>Build a well-organized academic foundation for your students and staff.</p>

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
            <h2>Academic Setup</h2>
            <p>Manage classes, sections, subjects and subject groups.</p>
            <span class="card-link">Open setup <x-icon name="arrow-right" /></span>
        </a>
        <a class="action-card accent-card" href="{{ route('institute.students.index') }}">
            <span class="tile-icon"><x-icon name="school" /></span>
            <h2>Admissions &amp; Students</h2>
            <p>Follow up inquiries, admit students and manage their records.</p>
            <span class="card-link">Open student directory <x-icon name="arrow-right" /></span>
        </a>
    </section>
@endsection
