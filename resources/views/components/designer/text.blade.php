@props(['label', 'prop', 'placeholder' => ''])
<div class="flex items-center gap-2 py-0.5">
    <label class="text-xs text-gray-500 w-24 flex-shrink-0 text-right">{{ $label }}</label>
    <input
        type="text"
        wire:model.live="editingProps.{{ $prop }}"
        placeholder="{{ $placeholder }}"
        class="flex-1 min-w-0 border border-gray-200 rounded px-2 py-0.5 text-xs text-gray-800 bg-white focus:outline-none focus:ring-1 focus:ring-blue-400 focus:border-blue-400"
    >
</div>
