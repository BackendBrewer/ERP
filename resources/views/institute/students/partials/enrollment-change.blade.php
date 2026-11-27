    <x-card :title="ucfirst($action) . ' student'" class="form-card" id="enrollment-action">
        <form class="form-stack" method="POST"
              action="{{ route('institute.students.enrollment.store', [
                  'student' => $student->id,
                  'action' => $action,
              ]) }}"
              data-loading-form
              @if ($action !== 'withdraw') data-admission-form @endif>
            @csrf
            <input type="hidden" name="enrollment_id" value="{{ $latest->id }}">

            @error('enrollment_id')
                <p class="field-error" role="alert">{{ $message }}</p>
            @enderror

            @if ($action === 'withdraw')
                <p>Confirm withdrawal to close this student’s current enrollment today.</p>
            @else
                @include('institute.students.partials.placement', ['defaultClassId' => $latest->section->academy_class_id])
            @endif

            <div class="form-actions">
                <button class="button button-primary" type="submit">
                    Confirm {{ $action }}
                </button>
                <a class="button button-secondary"
                   href="{{ route('institute.students.show', $student->id) }}">Cancel</a>
            </div>
        </form>
    </x-card>
