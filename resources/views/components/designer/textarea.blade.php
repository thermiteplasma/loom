@props(['label', 'prop', 'placeholder' => ''])
<div class="py-0.5">
    <label class="block text-xs text-gray-500 mb-1">{{ $label }}</label>
    <textarea
        wire:model.live="editingProps.{{ $prop }}"
        placeholder="{{ $placeholder }}"
        rows="3"
        class="w-full border border-gray-200 rounded px-2 py-1 text-xs text-gray-800 font-mono bg-white focus:outline-none focus:ring-1 focus:ring-blue-400 focus:border-blue-400 resize-y"
    ></textarea>
</div>
