@extends('layouts.app')

@section('title', 'Audit History | '.$institute->name)

@section('content')
<div class="mx-auto max-w-6xl">
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div><p class="text-sm font-medium text-indigo-600">{{ $institute->name }}</p><h1 class="mt-1 text-3xl font-bold text-slate-950 dark:text-white">Audit history</h1><p class="mt-2 text-sm text-slate-600">Institute staff and access changes recorded in this workspace.</p></div>
        <a href="{{ route('workspace') }}" class="rounded-lg border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800">Back to dashboard</a>
    </div>

    <section class="mt-7 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
        @if ($logs->isEmpty())
            <div class="px-6 py-16 text-center"><h2 class="font-semibold text-slate-900 dark:text-white">No audit events yet</h2><p class="mt-2 text-sm text-slate-500">Staff account and role changes will appear here.</p></div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500 dark:bg-slate-950"><tr><th class="px-5 py-3">Action</th><th class="px-5 py-3">Performed by</th><th class="px-5 py-3">Record</th><th class="px-5 py-3">Time</th></tr></thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @foreach ($logs as $log)
                            <tr><td class="px-5 py-4 font-medium text-slate-900 dark:text-slate-100">{{ ucwords(str_replace(['.', '_'], ' ', $log->action)) }}</td><td class="px-5 py-4 text-slate-600">{{ $log->actor?->name ?? 'Former user' }}</td><td class="px-5 py-4 text-slate-600">{{ class_basename($log->subject_type ?? 'Record') }} #{{ $log->subject_id ?? '—' }} @if ($log->metadata['role'] ?? false)<span class="text-slate-400">· {{ ucfirst($log->metadata['role']) }}</span>@endif</td><td class="px-5 py-4 whitespace-nowrap text-slate-500">{{ $log->created_at->format('M j, Y · g:i A') }}</td></tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="border-t border-slate-200 px-5 py-4 dark:border-slate-800">{{ $logs->links() }}</div>
        @endif
    </section>
</div>
@endsection
