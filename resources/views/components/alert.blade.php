@props(['type' => 'info', 'message' => ''])

@php
    $styles = [
        'success' => 'bg-green-100 border border-green-400 text-green-700',
        'error' => 'bg-red-100 border border-red-400 text-red-700',
        'info' => 'bg-blue-100 border border-blue-400 text-blue-700',
    ];

    $icons = [
        'success' => '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 
                      00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 
                      00-1.414 1.414l2 2a1 1 0 
                      001.414 0l4-4z" clip-rule="evenodd" />',
        'error' => '<path fill-rule="evenodd" d="M18 10a8 8 0 
                      11-16 0 8 8 0 0116 0zm-7-4a1 1 0 
                      00-2 0v3a1 1 0 
                      00.293.707l2 2a1 1 0 
                      001.414-1.414L11 9.586V6z" clip-rule="evenodd" />',
        'info' => '<path fill-rule="evenodd" d="M18 10a8 8 0 
                      11-16 0 8 8 0 0116 0zM9 9h2v6H9V9zm0-4h2v2H9V5z" 
                      clip-rule="evenodd" />',
    ];
@endphp

<div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show" x-transition x-cloak
    class="{{ $styles[$type] }} px-4 py-3 rounded-lg mb-4 flex items-center gap-3">
    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
        {!! $icons[$type] !!}
    </svg>
    <span>{{ $message }}</span>
</div>
