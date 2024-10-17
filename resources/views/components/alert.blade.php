@php
    // Tentukan warna berdasarkan jenis alert
    $alertColors = [
        'success' => 'bg-green-100 text-green-700 border-green-400',
        'danger'  => 'bg-red-100 text-red-700 border-red-400',
        'warning' => 'bg-yellow-100 text-yellow-700 border-yellow-400',
        'info'    => 'bg-blue-100 text-blue-700 border-blue-400',
    ];
@endphp

<div class="border-l-4 p-4 mb-4 {{ $alertColors[$type] ?? $alertColors['info'] }}">
    <p class="font-bold">{{ ucfirst($type) }}</p>
    {{-- Fungsi ucfirst() akan mengubah huruf pertama dari string $type menjadi huruf kapital. Jadi, misalnya jika tipe alert adalah 'success', maka yang ditampilkan di sini akan menjadi 'Success'. --}}
    <p>{{ $message }}</p>
</div>
