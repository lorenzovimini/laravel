@props([
    'record',
])

<td
    wire:loading.remove.delay
    wire:target="{{ implode(',', \Filament\Tables\Table::LOADING_TARGETS) }}"
    {{ $attributes->class(['filament-tables-actions-cell px-4 py-3 whitespace-nowrap bg-gray-100 dark:bg-gray-700']) }}
>
    {{ $slot }}
</td>
