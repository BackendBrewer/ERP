@if ($page->previousPageUrl() || $page->hasMorePages())
    <nav class="pagination" aria-label="{{ $label }}">
        @if ($page->previousPageUrl())
            <a class="button button-secondary" href="{{ $page->previousPageUrl() }}"><x-icon name="arrow-left" /> Previous</a>
        @endif

        @if ($page->hasMorePages())
            <a class="button button-secondary" href="{{ $page->nextPageUrl() }}">Next <x-icon name="arrow-right" /></a>
        @endif
    </nav>
@endif
