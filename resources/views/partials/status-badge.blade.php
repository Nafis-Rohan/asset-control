@php
    $assetStatuses = [
        'available' => 'bg-green-100 text-green-800',
        'assigned' => 'bg-blue-100 text-blue-800',
        'maintenance' => 'bg-red-100 text-red-800',
        'retired' => 'bg-gray-100 text-gray-800',
    ];

    $requestStatuses = [
        'pending' => 'bg-yellow-100 text-yellow-800',
        'approved' => 'bg-green-100 text-green-800',
        'denied' => 'bg-red-100 text-red-800',
        'fulfilled' => 'bg-indigo-100 text-indigo-800',
    ];

    $classes = ($type ?? 'asset') === 'request'
        ? ($requestStatuses[$status] ?? 'bg-gray-100 text-gray-800')
        : ($assetStatuses[$status] ?? 'bg-gray-100 text-gray-800');
@endphp

<span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $classes }}">
    {{ ucfirst($status) }}
</span>
