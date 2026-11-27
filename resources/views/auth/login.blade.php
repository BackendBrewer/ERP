@extends('layouts.app')

@section('title', 'Sign in')

@section('content')
    <div class="welcome-shell">
        <div class="login-shell">
            <aside class="login-visual">
                <a class="brand" href="{{ url('/') }}">
                    <span class="brand-mark" aria-hidden="true">A</span>
                    <span>Academy ERP</span>
                </a>
                <div class="login-art" aria-hidden="true"></div>
                <div class="login-visual-copy">
                    <span class="page-eyebrow">Academy workspace</span>
                    <h2>One place to keep learning in motion.</h2>
                    <p>Manage the foundations of your academy with a workspace designed for your team.</p>
                </div>
            </aside>

            <section class="login-content" aria-labelledby="sign-in-heading">
                <div class="login-inner">
                    <div class="brand login-brand">
                        <span class="brand-mark" aria-hidden="true">A</span>
                        <span>Academy ERP</span>
                    </div>
                    <span class="page-eyebrow">Staff access</span>
                    <h1 id="sign-in-heading">Welcome back</h1>
                    <p class="muted">Sign in with your staff account to continue.</p>

                    <form class="form-stack" method="POST" action="{{ route('login') }}" data-loading-form>
                        @csrf
                        <div class="field">
                            <label for="email">Email address<span class="required-mark" aria-hidden="true">*</span></label>
                            <input id="email" name="email" type="email" value="{{ old('email') }}"
                                   autocomplete="username" placeholder="name@academy.edu" required
                                   @error('email') aria-invalid="true" aria-describedby="email-error" @enderror>
                            @error('email')
                                <p class="field-error" id="email-error" role="alert">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="field">
                            <label for="password">Password<span class="required-mark" aria-hidden="true">*</span></label>
                            <div class="password-field">
                                <input id="password" name="password" type="password"
                                       autocomplete="current-password" placeholder="Enter your password" required
                                       @error('password') aria-invalid="true" aria-describedby="password-error" @enderror>
                                <button class="password-toggle" type="button" data-password-toggle="password"
                                        aria-controls="password" hidden>Show password</button>
                            </div>
                            @error('password')
                                <p class="field-error" id="password-error" role="alert">{{ $message }}</p>
                            @enderror
                        </div>
                        <button class="button button-primary" type="submit">Sign in <x-icon name="arrow-right" /></button>
                    </form>
                    <p class="login-helper">Need help signing in? Contact your academy administrator.</p>
                </div>
            </section>
        </div>
    </div>
@endsection
