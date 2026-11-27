@props(['name', 'class' => ''])

<svg {{ $attributes->merge(['class' => trim('icon ' . $class)]) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
    @switch($name)
        @case('home')<path d="m3 10 9-7 9 7v10a1 1 0 0 1-1 1h-6v-7h-4v7H4a1 1 0 0 1-1-1z"/>@break
        @case('school')<path d="m3 9 9-5 9 5-9 5z"/><path d="M5 11v5c2.5 2.7 11.5 2.7 14 0v-5M21 9v6"/>@break
        @case('building')<path d="M4 21V5l8-3v19M12 8h8v13M8 7v1m0 4v1m0 4v1m8-5v1m0 4v1M2 21h20"/>@break
        @case('book')<path d="M4 5.5A2.5 2.5 0 0 1 6.5 3H20v16H6.5A2.5 2.5 0 0 0 4 21z"/><path d="M4 5.5v13A2.5 2.5 0 0 1 6.5 16H20M8 7h8"/>@break
        @case('grid')<rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/>@break
        @case('search')<circle cx="10.8" cy="10.8" r="6.8"/><path d="m16 16 5 5"/>@break
        @case('bell')<path d="M18 9a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9M10 21h4"/>@break
        @case('sun')<circle cx="12" cy="12" r="4"/><path d="M12 2v2m0 16v2m10-10h-2M4 12H2m17.07 7.07-1.42-1.42M6.35 6.35 4.93 4.93m14.14 0-1.42 1.42M6.35 17.65l-1.42 1.42"/>@break
        @case('moon')<path d="M20.9 13A8.5 8.5 0 0 1 11 3.1 8.5 8.5 0 1 0 20.9 13Z"/>@break
        @case('menu')<path d="M4 6h16M4 12h16M4 18h16"/>@break
        @case('close')<path d="m18 6-12 12M6 6l12 12"/>@break
        @case('arrow-right')<path d="M5 12h14m-6-6 6 6-6 6"/>@break
        @case('arrow-left')<path d="M19 12H5m6 6-6-6 6-6"/>@break
        @case('check')<path d="m5 12 4 4L19 6"/>@break
        @case('plus')<path d="M12 5v14m-7-7h14"/>@break
        @case('spark')<path d="m12 3 1.8 5.2L19 10l-5.2 1.8L12 17l-1.8-5.2L5 10l5.2-1.8L12 3ZM19 15l1 2.5 2.5 1-2.5 1-1 2.5-1-2.5-2.5-1 2.5-1z"/>@break
        @case('layers')<path d="m12 3 9 5-9 5-9-5z"/><path d="m3 12 9 5 9-5M3 16l9 5 9-5"/>@break
    @endswitch
</svg>
