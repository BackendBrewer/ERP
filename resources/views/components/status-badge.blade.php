@props(['label', 'variant' => 'info'])

<span {{ $attributes->class(['badge', 'badge-success' => $variant === 'success', 'badge-warning' => $variant === 'warning', 'badge-danger' => $variant === 'danger', 'badge-info' => !in_array($variant, ['success', 'warning', 'danger'], true)]) }}>{{ $label }}</span>
