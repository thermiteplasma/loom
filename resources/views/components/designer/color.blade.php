@props(['label', 'prop'])
<div class="flex items-center gap-2 py-0.5">
    <label class="text-xs text-gray-500 w-24 flex-shrink-0 text-right">{{ $label }}</label>
    <div class="flex items-center gap-1 flex-1 min-w-0">
        <input
            type="color"
            wire:model.live="editingProps.{{ $prop }}"
            class="w-6 h-6 rounded border border-gray-200 cursor-pointer p-0 bg-white flex-shrink-0"
            title="{{ $label }}"
        >
        <input
            type="text"
            wire:model.live="editingProps.{{ $prop }}"
            placeholder="#000000"
            class="flex-1 min-w-0 border border-gray-200 rounded px-2 py-0.5 text-xs text-gray-800 font-mono bg-white focus:outline-none focus:ring-1 focus:ring-blue-400 focus:border-blue-400"
        >
    </div>
</div>
