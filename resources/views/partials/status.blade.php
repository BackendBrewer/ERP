@if (session('status'))
    <div class="toast-stack" aria-live="polite" aria-atomic="true">
        <p class="notice" role="status" data-auto-dismiss>
            <x-icon name="check" />
            <span>{{ session('status') }}</span>
        </p>
    </div>
@endif
