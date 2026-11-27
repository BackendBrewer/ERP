@props(['title' => null, 'subtitle' => null, 'class' => ''])

<section {{ $attributes->merge(['class' => trim('surface ' . $class)]) }}>
    @if ($title || $subtitle)
        <div class="surface-heading">
            <div>
                @if ($title)<h2>{{ $title }}</h2>@endif
                @if ($subtitle)<p>{{ $subtitle }}</p>@endif
            </div>
            @isset($actions)<div class="page-heading-actions">{{ $actions }}</div>@endisset
        </div>
    @endif
    {{ $slot }}
</section>
