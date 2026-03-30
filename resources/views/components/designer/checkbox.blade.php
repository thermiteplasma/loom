@props(['label', 'prop'])
<label class="flex items-center gap-2 py-0.5 cursor-pointer">
    <input
        type="checkbox"
        wire:model.live="editingProps.{{ $prop }}"
        class="rounded border-gray-300 text-blue-600 focus:ring-blue-400 focus:ring-1 cursor-pointer"
    >
    <span class="text-xs text-gray-700">{{ $label }}</span>
</label>
