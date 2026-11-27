@props(['title', 'description' => null, 'eyebrow' => null, 'breadcrumbs' => []])

@if (count($breadcrumbs))
    <nav class="breadcrumbs" aria-label="Breadcrumb">
        @foreach ($breadcrumbs as $index => $breadcrumb)
            @if ($index > 0)<span aria-hidden="true">/</span>@endif
            @if (!empty($breadcrumb['url']))
                <a href="{{ $breadcrumb['url'] }}">{{ $breadcrumb['label'] }}</a>
            @else
                <span aria-current="page">{{ $breadcrumb['label'] }}</span>
            @endif
        @endforeach
    </nav>
@endif

<header class="page-heading">
    <div>
        @if ($eyebrow)<span class="page-eyebrow">{{ $eyebrow }}</span>@endif
        <h1>{{ $title }}</h1>
        @if ($description)<p>{{ $description }}</p>@endif
    </div>
    @if ($slot->isNotEmpty())<div class="page-heading-actions">{{ $slot }}</div>@endif
</header>
