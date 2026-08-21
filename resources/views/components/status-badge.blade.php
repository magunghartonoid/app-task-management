@props(['status'])

@php
    $map = [
        'pending'     => ['label' => 'Menunggu', 'class' => 'bg-secondary'],
        'in_progress' => ['label' => 'Diproses', 'style' => 'background-color:#0d6efd;color:#fff;'],
        'testing'     => ['label' => 'Pengujian', 'class' => 'bg-warning'],
        'completed'   => ['label' => 'Selesai', 'class' => 'bg-success'],
        'canceled'    => ['label' => 'Dibatalkan', 'class' => 'bg-danger'],
    ];
    $item = $map[$status] ?? ['label' => $status, 'class' => 'bg-secondary'];
@endphp

<span class="badge {{ $item['class'] ?? '' }}" @if (isset($item['style'])) style="{{ $item['style'] }}" @endif>
    {{ $item['label'] }}
</span>
