@props(['priority'])

@php
    $map = [
        'urgent' => ['label' => 'Mendesak', 'class' => 'bg-danger'],
        'high'   => ['label' => 'Tinggi', 'style' => 'background-color:#fd7e14;color:#fff;'],
        'medium' => ['label' => 'Sedang', 'class' => 'bg-warning'],
        'low'    => ['label' => 'Rendah', 'class' => 'bg-info'],
    ];
    $item = $map[$priority] ?? ['label' => $priority, 'class' => 'bg-secondary'];
@endphp

<span class="badge {{ $item['class'] ?? '' }}" @if (isset($item['style'])) style="{{ $item['style'] }}" @endif>
    {{ $item['label'] }}
</span>
