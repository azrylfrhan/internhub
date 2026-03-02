@props(['status', 'error'])

@if ($error ?? session('error'))
    <div {{ $attributes->merge(['class' => 'font-medium text-sm text-red-600 bg-red-100 dark:bg-red-900/30 dark:text-red-400 border border-red-400 dark:border-red-800 rounded-lg px-4 py-3']) }}>
        {{ $error ?? session('error') }}
    </div>
@endif

@if ($status)
    <div {{ $attributes->merge(['class' => 'font-medium text-sm text-green-600 bg-green-100 dark:bg-green-900/30 dark:text-green-400 border border-green-400 dark:border-green-800 rounded-lg px-4 py-3']) }}>
        {{ $status }}
    </div>
@endif
