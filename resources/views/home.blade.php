
@extends('layouts.app')

@section('title', 'Welcome | Academy ERP')

@section('content')

    <section class="py-10 sm:py-16">

        <div class="max-w-3xl">
            <p class="mb-4 text-sm font-semibold uppercase tracking-widest text-indigo-600">
                Education Management Platform
            </p>

            <h1 class="text-4xl font-bold tracking-tight text-slate-950 sm:text-5xl">
                Manage your academy from one connected platform.
            </h1>

            <p class="mt-6 text-lg leading-8 text-slate-600">
                Admissions, academic operations, fee management,
                full accounting, attendance and examinations —
                designed for modern educational institutes.
            </p>

            <div class="mt-8 inline-flex items-center rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-900">
                ERP development is in progress. Operational modules are not available yet.
            </div>
        </div>

    </section>

    <section class="grid gap-5 pb-12 md:grid-cols-3">

        @foreach ([
            [
                'title' => 'Student Management',
                'description' => 'Admissions, student profiles, enrollment and academic history.',
            ],
            [
                'title' => 'Finance & Accounting',
                'description' => 'Fee collection, financial records, double-entry accounting and reports.',
            ],
            [
                'title' => 'Academic Operations',
                'description' => 'Attendance, examinations, courses, batches and results.',
            ],
        ] as $module)

            <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

                <h2 class="text-lg font-semibold text-slate-900">
                    {{ $module['title'] }}
                </h2>

                <p class="mt-3 text-sm leading-6 text-slate-600">
                    {{ $module['description'] }}
                </p>

                <span class="mt-6 inline-block text-xs font-medium uppercase tracking-wide text-slate-400">
                    Planned Module
                </span>

            </article>

        @endforeach

    </section>

@endsection