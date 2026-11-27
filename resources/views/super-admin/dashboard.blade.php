@extends('layouts.app')

@section('title', 'Platform overview')

@section('content')
    <section class="hero">
        <div class="hero-content">
            <span class="page-eyebrow">Platform workspace</span>
            <h1>Keep every academy moving forward.</h1>
            <p>Create academy workspaces and give their Institute Admins the access they need to get started.</p>
            <div class="hero-actions">
                <a class="button button-amber" href="{{ route('super-admin.academies.create') }}">Create an academy <x-icon name="plus" /></a>
                <a class="button button-secondary" href="{{ route('super-admin.academies.index') }}">View academies</a>
            </div>
        </div>
    </section>

    <div class="section-title-row">
        <div>
            <h2>Platform essentials</h2>
            <p>Manage academy workspaces from one place.</p>
        </div>
    </div>

    <section class="dashboard-grid" aria-label="Platform actions">
        <a class="action-card" href="{{ route('super-admin.academies.index') }}">
            <span class="tile-icon"><x-icon name="building" /></span>
            <h2>Manage academies</h2>
            <p>Browse academy workspaces and set up Institute Admin access.</p>
            <span class="card-link">Open academy directory <x-icon name="arrow-right" /></span>
        </a>
        <a class="action-card accent-card" href="{{ route('super-admin.academies.create') }}">
            <span class="tile-icon"><x-icon name="plus" /></span>
            <h2>Create an academy</h2>
            <p>Register an academy with a name and unique workspace slug.</p>
            <span class="card-link">Start setup <x-icon name="arrow-right" /></span>
        </a>
    </section>
@endsection
