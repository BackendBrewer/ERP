@props(['title', 'description' => null, 'icon' => 'layers'])

<div {{ $attributes->merge(['class' => 'empty-state']) }}>
    <span class="tile-icon"><x-icon :name="$icon" /></span>
    <h2>{{ $title }}</h2>
    @if ($description)<p>{{ $description }}</p>@endif
    @if ($slot->isNotEmpty())<div class="page-heading-actions">{{ $slot }}</div>@endif
</div>
