@extends('layouts.app')

@section('title', 'Admission Inquiries')

@section('content')
    <x-page-header
        title="Admission Inquiries"
        description="Record prospective students and schedule follow-ups."
        eyebrow="Admissions"
        :breadcrumbs="[['label' => 'Workspace', 'url' => route('institute.dashboard')], ['label' => 'Admissions'], ['label' => 'Admission Inquiries']]"
    />
    @include('partials.status')

    <div class="management-grid">
        <x-card title="Saved inquiries" subtitle="Prospective students and their follow-up details." class="directory">
            <div class="table-toolbar">
                <span class="muted">{{ $inquiries->count() }} shown on this page</span>
                @if ($inquiries->count())
                    <label class="table-search">
                        <x-icon name="search" />
                        <input type="search" data-table-search="admission-inquiries-table" placeholder="Search inquiries…" aria-label="Search admission inquiries">
                    </label>
                @endif
            </div>

            @if ($inquiries->count())
                <div class="table-wrap">
                    <table class="directory-table" id="admission-inquiries-table" data-global-searchable>
                        <caption class="sr-only">Saved admission inquiries and follow-up dates</caption>
                        <thead>
                            <tr>
                                <th scope="col">Prospective student</th>
                                <th scope="col">Interested class</th>
                                <th scope="col">Follow-up</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($inquiries as $inquiry)
                                <tr data-searchable>
                                    <th scope="row">
                                        <span class="row-identity">
                                            <span class="tile-icon"><x-icon name="school" /></span>
                                            <span>
                                                <strong>{{ $inquiry->student_name }}</strong>
                                                <span class="table-secondary-text">{{ $inquiry->contact_phone }}</span>
                                            </span>
                                        </span>
                                    </th>
                                    <td>{{ $inquiry->academyClass?->name ?? 'Class not confirmed' }}</td>
                                    <td>
                                        @if ($inquiry->follow_up_on)
                                            <time class="badge badge-warning" datetime="{{ $inquiry->follow_up_on->toDateString() }}">
                                                {{ $inquiry->follow_up_on->format('d M Y') }}
                                            </time>
                                        @else
                                            <span class="muted">Not scheduled</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="search-empty" data-search-empty hidden>No inquiries match your search.</div>
                </div>
            @else
                <x-empty-state
                    :title="$inquiries->currentPage() > 1 ? 'No inquiries on this page' : 'No inquiries yet'"
                    :description="$inquiries->currentPage() > 1 ? null : 'Add a prospective student and keep their next follow-up in one place.'"
                    icon="school"
                />
            @endif

            @include('partials.pagination', ['page' => $inquiries, 'label' => 'Inquiry pages'])
        </x-card>

        <x-card title="New inquiry" subtitle="Capture a prospective student’s details." class="form-card">
            <form class="form-stack" method="POST" action="{{ route('institute.admission-inquiries.store') }}" data-loading-form>
                @csrf

                <div class="field">
                    <label for="student_name">Student name<span class="required-mark" aria-hidden="true">*</span></label>
                    <input id="student_name" name="student_name" value="{{ old('student_name') }}"
                           maxlength="150" placeholder="Enter student’s full name" autocomplete="name" required
                           @error('student_name') aria-invalid="true" aria-describedby="student-name-error" @enderror>
                    @error('student_name')
                        <p class="field-error" id="student-name-error" role="alert">{{ $message }}</p>
                    @enderror
                </div>

                <div class="field">
                    <label for="guardian_name">Guardian name</label>
                    <input id="guardian_name" name="guardian_name" value="{{ old('guardian_name') }}"
                           maxlength="150" placeholder="Enter parent or guardian name">
                    @error('guardian_name')
                        <p class="field-error" role="alert">{{ $message }}</p>
                    @enderror
                </div>

                <div class="field">
                    <label for="contact_phone">Contact number<span class="required-mark" aria-hidden="true">*</span></label>
                    <input id="contact_phone" name="contact_phone" type="tel" inputmode="tel" autocomplete="tel"
                           value="{{ old('contact_phone') }}" maxlength="25" placeholder="e.g. 0300 1234567" required
                           @error('contact_phone') aria-invalid="true" aria-describedby="contact-phone-help contact-phone-error" @enderror>
                    <p class="help" id="contact-phone-help">Include the country code if needed.</p>
                    @error('contact_phone')
                        <p class="field-error" id="contact-phone-error" role="alert">{{ $message }}</p>
                    @enderror
                </div>

                <div class="field">
                    <label for="academy_class_id">Interested class</label>
                    <select id="academy_class_id" name="academy_class_id"
                            @error('academy_class_id') aria-invalid="true" aria-describedby="academy-class-error" @enderror>
                        <option value="">Class not confirmed</option>
                        @foreach ($classes as $class)
                            <option value="{{ $class->id }}" @selected((string) old('academy_class_id') === (string) $class->id)>
                                {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('academy_class_id')
                        <p class="field-error" id="academy-class-error" role="alert">{{ $message }}</p>
                    @enderror
                </div>

                <div class="field">
                    <label for="follow_up_on">Follow-up date</label>
                    <input id="follow_up_on" name="follow_up_on" type="date" value="{{ old('follow_up_on') }}"
                           @error('follow_up_on') aria-invalid="true" aria-describedby="follow-up-error" @enderror>
                    @error('follow_up_on')
                        <p class="field-error" id="follow-up-error" role="alert">{{ $message }}</p>
                    @enderror
                </div>

                <div class="field">
                    <label for="notes">Notes</label>
                    <textarea id="notes" name="notes" rows="4" maxlength="2000"
                              placeholder="Add any helpful context for the next conversation">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="field-error" role="alert">{{ $message }}</p>
                    @enderror
                </div>

                <button class="button button-primary" type="submit">Save inquiry <x-icon name="arrow-right" /></button>
            </form>
        </x-card>
    </div>
@endsection
