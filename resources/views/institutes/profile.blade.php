
@extends('layouts.app')

@section('title', 'Institute Profile | Academy ERP')

@section('content')

<section class="max-w-4xl">

    <div class="mb-8">
        <p class="text-sm font-semibold uppercase tracking-wide text-indigo-600">
            Institute Management
        </p>

        <h1 class="mt-2 text-3xl font-bold">
            {{ $institute->name }}
        </h1>

        <p class="mt-2 text-slate-500">
            Institute Profile
        </p>
    </div>

    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">

        <dl class="grid gap-6 sm:grid-cols-2">

            <div>
                <dt class="text-sm text-slate-500">Institute Name</dt>
                <dd class="mt-1 font-medium">{{ $institute->name }}</dd>
            </div>

            <div>
                <dt class="text-sm text-slate-500">Status</dt>
                <dd class="mt-1 font-medium">{{ ucfirst($institute->status) }}</dd>
            </div>

            <div>
                <dt class="text-sm text-slate-500">Address</dt>
                <dd class="mt-1 font-medium">{{ $institute->profile?->address ?? 'Not provided' }}</dd>
            </div>

            <div>
                <dt class="text-sm text-slate-500">City</dt>
                <dd class="mt-1 font-medium">{{ $institute->profile?->city ?? 'Not provided' }}</dd>
            </div>

            <div>
                <dt class="text-sm text-slate-500">Phone</dt>
                <dd class="mt-1 font-medium">{{ $institute->profile?->phone ?? 'Not provided' }}</dd>
            </div>

            <div>
                <dt class="text-sm text-slate-500">Alternate Phone</dt>
                <dd class="mt-1 font-medium">{{ $institute->profile?->alternate_phone ?? 'Not provided' }}</dd>
            </div>

            <div>
                <dt class="text-sm text-slate-500">Email</dt>
                <dd class="mt-1 font-medium">{{ $institute->profile?->email ?? 'Not provided' }}</dd>
            </div>

            <div>
                <dt class="text-sm text-slate-500">Website</dt>
                <dd class="mt-1 font-medium">{{ $institute->profile?->website ?? 'Not provided' }}</dd>
            </div>

        </dl>

    </div>

    <a href="{{ route('workspace') }}"
       class="mt-6 inline-block text-sm font-medium text-indigo-600 hover:underline">
        Back to Workspace
    </a>

</section>

@endsection
