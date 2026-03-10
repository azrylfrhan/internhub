<div {{ $attributes->merge(['class' => 'fixed inset-0 z-[80] bg-white/45 dark:bg-gray-900/45 backdrop-blur-sm']) }} x-cloak>
    <div class="absolute left-0 top-0 h-1 w-full overflow-hidden bg-blue-100/70 dark:bg-blue-950/40">
        <div class="loading-progress-bar h-full w-1/3 bg-blue-600"></div>
    </div>

    <div class="flex h-full items-center justify-center">
        <div class="relative flex h-14 w-14 items-center justify-center rounded-full bg-white/80 shadow-sm ring-1 ring-blue-100 dark:bg-gray-800/80 dark:ring-blue-900/50">
            <span class="absolute inline-flex h-10 w-10 rounded-full bg-blue-500/25 animate-ping"></span>
            <img src="/logo-bps.png" alt="InternHub" class="h-7 w-7 animate-spin [animation-duration:1.4s]" />
        </div>
    </div>
</div>
