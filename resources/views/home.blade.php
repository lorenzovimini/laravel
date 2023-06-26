@props([
    'maxContentWidth' => null,
])

<x-filament::layouts.base :title="$title">
    <div class="filament-app-layout flex h-full w-full overflow-x-clip">
        <div
                x-data="{}"
                x-cloak
                x-show="$store.sidebar.isOpen"
                x-transition.opacity.500ms
                x-on:click="$store.sidebar.close()"
                class="filament-sidebar-close-overlay fixed inset-0 z-20 w-full h-full bg-gray-900/50 lg:hidden"
        ></div>

    </div>
</x-filament::layouts.base>
