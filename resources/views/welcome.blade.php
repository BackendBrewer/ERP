@extends('layouts.app')

@section('title', 'Welcome')

@section('content')
    <div class="welcome-shell">
        <section class="welcome-card">
            <div class="welcome-orb" aria-hidden="true"><x-icon name="school" /></div>
            <a class="brand" href="{{ url('/') }}">
                <span class="brand-mark" aria-hidden="true">A</span>
                <span>Academy ERP</span>
            </a>
            <span class="page-eyebrow welcome-eyebrow">Academy management workspace</span>
            <h1>Make room for better learning.</h1>
            <p>Bring the everyday foundations of your academy into one clear, organized workspace.</p>
            <div class="welcome-actions">
                @if (Route::has('login'))
                    @auth
                        <a class="button button-primary" href="{{ url('/dashboard') }}">Open your dashboard <x-icon name="arrow-right" /></a>
                    @else
                        <a class="button button-primary" href="{{ route('login') }}">Staff sign in <x-icon name="arrow-right" /></a>
                        @if (Route::has('register'))
                            <a class="button button-secondary" href="{{ route('register') }}">Register</a>
                        @endif
                    @endauth
                @endif
            </div>
        </section>
    </div>
@endsection
