<div class="w-full max-w-md mx-auto mt-6 px-6 py-6 bg-white/80 rounded-xl shadow-md">
    @isset($logo)
        <div class="flex justify-center mb-4">
            {{ $logo }}
        </div>
    @endisset

    {{ $slot }}
</div>
