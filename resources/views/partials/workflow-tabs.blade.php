<nav class="workflow-tabs" aria-label="{{ $label }}">
    @foreach ($tabs as [$title, $routeName])
        @php($current = request()->routeIs($routeName))
        <a href="{{ route($routeName) }}"
           @class(['button', 'button-primary' => $current, 'button-secondary' => ! $current])
           @if ($current) aria-current="page" @endif>{{ $title }}</a>
    @endforeach
</nav>
