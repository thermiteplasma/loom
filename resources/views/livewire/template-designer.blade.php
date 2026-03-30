<div class="flex h-full" x-data>

    {{-- ======================================================================
         LEFT PANEL — Element Palette
    ====================================================================== --}}
    <aside class="w-48 flex-shrink-0 bg-gray-800 text-white flex flex-col overflow-y-auto select-none">

        <div class="px-3 py-3 border-b border-gray-700">
            <div class="text-xs font-semibold uppercase tracking-widest text-gray-400">Loom Designer</div>
        </div>

        {{-- Report settings shortcut --}}
        <div class="px-3 py-2 border-b border-gray-700">
            <div class="text-xs text-gray-400 mb-1 uppercase tracking-wider">Report</div>
            <button
                class="w-full text-left text-xs px-2 py-1 rounded bg-gray-700 hover:bg-gray-600 text-gray-200 transition-colors duration-100"
                x-on:click="$dispatch('loom:open-report-settings')"
            >
                Page Settings
            </button>
        </div>

        {{-- Element palette --}}
        <div class="px-3 py-2 flex-1">
            <div class="text-xs text-gray-400 mb-2 uppercase tracking-wider">Elements</div>
            <div class="space-y-1">

                @foreach ([
                    ['type' => 'static-text',  'label' => 'Static Text',   'icon' => 'T'],
                    ['type' => 'field',         'label' => 'Field',         'icon' => '{}'],
                    ['type' => 'page-number',   'label' => 'Page Number',   'icon' => '#'],
                    ['type' => 'total-pages',   'label' => 'Total Pages',   'icon' => '##'],
                    ['type' => 'current-date',  'label' => 'Current Date',  'icon' => '📅'],
                    ['type' => 'line',          'label' => 'Line',          'icon' => '—'],
                    ['type' => 'rectangle',     'label' => 'Rectangle',     'icon' => '▭'],
                    ['type' => 'image',         'label' => 'Image',         'icon' => '🖼'],
                    ['type' => 'row',           'label' => 'Row',           'icon' => '☰'],
                    ['type' => 'column',        'label' => 'Column',        'icon' => '▏'],
                    ['type' => 'frame',         'label' => 'Frame',         'icon' => '⬜'],
                ] as $item)
                    <div
                        draggable="true"
                        class="flex items-center gap-2 px-2 py-1 rounded text-xs text-gray-200 bg-gray-700 hover:bg-indigo-600 cursor-grab transition-colors duration-100"
                        x-on:dragstart="$dispatch('loom:palette-drag', { type: '{{ $item['type'] }}' })"
                        x-on:dragend="$dispatch('loom:palette-dragend')"
                        title="Drag to a band"
                    >
                        <span class="w-5 text-center font-mono text-gray-400">{{ $item['icon'] }}</span>
                        <span>{{ $item['label'] }}</span>
                    </div>
                @endforeach

            </div>
        </div>

    </aside>

    {{-- ======================================================================
         CENTER PANEL — Band Canvas
    ====================================================================== --}}
    <main
        class="flex-1 overflow-y-auto bg-gray-100 p-4"
        x-data="{
            draggingType: null,
            init() {
                window.addEventListener('loom:palette-drag', e => { this.draggingType = e.detail.type; });
                window.addEventListener('loom:palette-dragend', () => { this.draggingType = null; });
            }
        }"
        x-on:click.self="$wire.deselect()"
    >

        {{-- Bands --}}
        @foreach ($bands as $bandIndex => $band)
            <div
                wire:key="band-{{ $band['type'] }}"
                class="mb-2 bg-white border border-gray-300 rounded shadow-sm overflow-hidden"
                x-data="{
                    dragOver: false,
                    handleDrop(e) {
                        e.preventDefault();
                        this.dragOver = false;
                        const type = e.dataTransfer?.getData('text/plain') ?? null;
                        if (type) $wire.addElement({{ $bandIndex }}, type);
                    }
                }"
            >
                {{-- Band header bar --}}
                <div
                    class="flex items-center justify-between px-3 py-1 bg-gray-200 border-b border-gray-300 cursor-pointer select-none"
                    x-on:click="$wire.selectBand({{ $bandIndex }})"
                    title="Click to edit band properties"
                >
                    <div class="flex items-center gap-2">
                        <button
                            class="text-gray-500 hover:text-gray-700 text-xs w-4 text-center transition-colors"
                            x-on:click.stop="$wire.toggleBandCollapsed({{ $bandIndex }})"
                            title="{{ $band['collapsed'] ? 'Expand' : 'Collapse' }}"
                        >
                            {{ $band['collapsed'] ? '▶' : '▼' }}
                        </button>
                        <span class="text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            {{ $band['label'] }}
                        </span>
                        @if(count($band['elements']) > 0)
                            <span class="text-xs text-gray-400">({{ count($band['elements']) }})</span>
                        @endif
                    </div>

                    {{-- Selected indicator --}}
                    @if($selectedBandIndex === $bandIndex && $selectedType === 'band')
                        <span class="text-xs text-indigo-600 font-medium">● selected</span>
                    @endif
                </div>

                {{-- Band body / drop zone --}}
                @unless($band['collapsed'])
                    <div
                        class="min-h-16 p-2 transition-colors duration-100"
                        x-bind:class="dragOver ? 'bg-indigo-50 border-2 border-dashed border-indigo-300' : 'bg-white'"
                        x-on:dragover.prevent="dragOver = true"
                        x-on:dragleave="dragOver = false"
                        x-on:drop="handleDrop($event)"
                    >
                        @forelse ($band['elements'] as $elementIndex => $element)
                            <div
                                wire:key="{{ $element['id'] }}"
                                draggable="true"
                                class="flex items-center justify-between gap-2 px-2 py-1 mb-1 rounded text-xs border cursor-pointer transition-colors duration-100 group
                                    {{ ($selectedBandIndex === $bandIndex && $selectedElementIndex === $elementIndex)
                                        ? 'bg-indigo-100 border-indigo-300 text-indigo-700 ring-2 ring-blue-400 ring-offset-1'
                                        : 'bg-gray-50 border-gray-200 text-gray-700 hover:bg-indigo-50 hover:border-indigo-200' }}"
                                x-on:click.stop="$wire.selectElement({{ $bandIndex }}, {{ $elementIndex }})"
                                x-on:dragstart="$event.dataTransfer.setData('text/plain', '{{ $element['type'] }}')"
                                title="{{ $element['type'] }}"
                            >
                                <div class="flex items-center gap-2 flex-1 min-w-0">
                                    <span class="font-mono text-gray-400 shrink-0">{{ $this->elementIcon($element['type']) }}</span>
                                    <span class="font-medium shrink-0">{{ $this->elementLabel($element['type']) }}</span>
                                    @if(!empty($element['props']['content']))
                                        <span class="text-gray-400 truncate">— {{ Str::limit($element['props']['content'], 30) }}</span>
                                    @endif
                                </div>

                                {{-- Element actions --}}
                                <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-colors shrink-0">
                                    <button
                                        class="px-1 text-gray-400 hover:text-gray-700"
                                        x-on:click.stop="$wire.moveElementUp({{ $bandIndex }}, {{ $elementIndex }})"
                                        title="Move up"
                                    >↑</button>
                                    <button
                                        class="px-1 text-gray-400 hover:text-gray-700"
                                        x-on:click.stop="$wire.moveElementDown({{ $bandIndex }}, {{ $elementIndex }})"
                                        title="Move down"
                                    >↓</button>
                                    <button
                                        class="px-1 text-gray-400 hover:text-red-500"
                                        x-on:click.stop="$wire.removeElement({{ $bandIndex }}, {{ $elementIndex }})"
                                        title="Remove"
                                    >✕</button>
                                </div>
                            </div>
                        @empty
                            <div class="text-xs text-gray-400 italic text-center py-4 pointer-events-none">
                                Drop elements here
                            </div>
                        @endforelse
                    </div>
                @endunless
            </div>
        @endforeach

        {{-- Generate button --}}
        <div class="mt-4 flex justify-end">
            <button
                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded shadow transition-colors duration-100"
                x-on:click="$wire.generate()"
            >
                Generate Blade Code
            </button>
        </div>

    </main>

    {{-- ======================================================================
         RIGHT PANEL — Properties
    ====================================================================== --}}
    <aside class="w-72 flex-shrink-0 bg-white border-l border-gray-200 flex flex-col overflow-y-auto">

        <div class="px-4 py-3 border-b border-gray-200 bg-gray-50">
            <div class="text-xs font-semibold uppercase tracking-wider text-gray-500">Properties</div>
            @if($selectedType === 'element' && $selectedBandIndex !== null && $selectedElementIndex !== null)
                <div class="text-sm font-medium text-gray-800 mt-0.5">
                    {{ $this->elementLabel($bands[$selectedBandIndex]['elements'][$selectedElementIndex]['type']) }}
                </div>
            @elseif($selectedType === 'band' && $selectedBandIndex !== null)
                <div class="text-sm font-medium text-gray-800 mt-0.5">
                    {{ $bands[$selectedBandIndex]['label'] }} Band
                </div>
            @else
                <div class="text-sm text-gray-400 mt-0.5 italic">Nothing selected</div>
            @endif
        </div>

        <div class="flex-1 overflow-y-auto p-3">

            @if($selectedType === 'element' && $selectedBandIndex !== null && $selectedElementIndex !== null)
                @php $elementType = $bands[$selectedBandIndex]['elements'][$selectedElementIndex]['type']; @endphp

                {{-- Content / Expression --}}
                @if(in_array($elementType, ['static-text', 'field', 'column', 'frame']))
                    <div class="mb-4">
                        <x-loom-designer-prop-section label="Content" />
                        <x-loom-designer-textarea label="Text / Expression" prop="content" />
                    </div>
                @endif

                @if($elementType === 'image')
                    <div class="mb-4">
                        <x-loom-designer-prop-section label="Image" />
                        <x-loom-designer-text label="Src" prop="src" />
                        <x-loom-designer-text label="Alt" prop="alt" />
                    </div>
                @endif

                @if($elementType === 'current-date')
                    <div class="mb-4">
                        <x-loom-designer-prop-section label="Date" />
                        <x-loom-designer-text label="Format (e.g. d/m/Y)" prop="format" />
                    </div>
                @endif

                @if($elementType === 'page-number')
                    <div class="mb-4">
                        <x-loom-designer-prop-section label="Page Number" />
                        <x-loom-designer-checkbox label="Show Total (e.g. 1 of 5)" prop="showTotal" />
                    </div>
                @endif

                @if($elementType === 'row')
                    <div class="mb-4">
                        <x-loom-designer-prop-section label="Row Layout" />
                        <x-loom-designer-select label="Justify" prop="justify" :options="[''=>'—','flex-start'=>'Start','center'=>'Center','flex-end'=>'End','space-between'=>'Space Between','space-around'=>'Space Around']" />
                        <x-loom-designer-select label="Items" prop="items" :options="[''=>'—','flex-start'=>'Top','center'=>'Center','flex-end'=>'Bottom','stretch'=>'Stretch']" />
                        <x-loom-designer-text label="Gap" prop="gap" placeholder="e.g. 4px" />
                    </div>
                @endif

                @if($elementType === 'column')
                    <div class="mb-4">
                        <x-loom-designer-prop-section label="Column Layout" />
                        <x-loom-designer-text label="Flex" prop="flex" placeholder="e.g. 1, 2" />
                    </div>
                @endif

                {{-- Typography (for text-bearing elements) --}}
                @if(in_array($elementType, ['static-text', 'field', 'column', 'frame', 'rectangle']))
                    @include('loom::livewire.partials.prop-typography')
                @endif

                {{-- Box model --}}
                @if(!in_array($elementType, ['page-number', 'total-pages', 'current-date']))
                    @include('loom::livewire.partials.prop-box-model')
                @endif

                {{-- Borders --}}
                @if(!in_array($elementType, ['page-number', 'total-pages', 'current-date']))
                    @include('loom::livewire.partials.prop-borders')
                @endif

                {{-- Background --}}
                @if(!in_array($elementType, ['line', 'page-number', 'total-pages', 'current-date']))
                    @include('loom::livewire.partials.prop-background')
                @endif

            @elseif($selectedType === 'band' && $selectedBandIndex !== null)

                {{-- Band properties --}}
                <div class="mb-4">
                    <x-loom-designer-prop-section label="Band" />
                    <x-loom-designer-color label="Background" prop="background" />
                    <x-loom-designer-text label="Padding" prop="padding" placeholder="e.g. 4mm" />
                    <x-loom-designer-text label="Min Height" prop="minHeight" placeholder="e.g. 10mm" />
                </div>
                <div class="mb-4">
                    <x-loom-designer-prop-section label="Borders" />
                    <x-loom-designer-checkbox label="Border Top" prop="borderTop" />
                    <x-loom-designer-checkbox label="Border Bottom" prop="borderBottom" />
                    <x-loom-designer-color label="Border Color" prop="borderColor" />
                </div>

            @else

                <div class="text-xs text-gray-400 italic text-center mt-8">
                    Select an element or band to edit its properties.
                </div>

            @endif

        </div>
    </aside>

    {{-- ======================================================================
         REPORT SETTINGS MODAL
    ====================================================================== --}}
    <div
        x-data="{ open: false }"
        x-on:loom:open-report-settings.window="open = true"
        x-show="open"
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40"
        x-on:click.self="open = false"
    >
        <div class="bg-white rounded-lg shadow-md w-80 overflow-hidden">
            <div class="px-4 py-3 border-b border-gray-200 flex items-center justify-between bg-gray-50">
                <span class="text-sm font-semibold text-gray-700">Report / Page Settings</span>
                <button class="text-gray-400 hover:text-gray-600" x-on:click="open = false">✕</button>
            </div>
            <div class="p-4 space-y-3">
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Page Size</label>
                    <select wire:model.live="reportProps.size" class="w-full border border-gray-300 rounded px-2 py-1 text-sm">
                        <option value="A4">A4</option>
                        <option value="A3">A3</option>
                        <option value="Letter">Letter</option>
                        <option value="Legal">Legal</option>
                        <option value="A5">A5</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Orientation</label>
                    <select wire:model.live="reportProps.orientation" class="w-full border border-gray-300 rounded px-2 py-1 text-sm">
                        <option value="portrait">Portrait</option>
                        <option value="landscape">Landscape</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Margin</label>
                    <input wire:model.live="reportProps.margin" type="text" placeholder="e.g. 10mm" class="w-full border border-gray-300 rounded px-2 py-1 text-sm">
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Font Family</label>
                    <input wire:model.live="reportProps.fontFamily" type="text" placeholder="e.g. DejaVu Sans" class="w-full border border-gray-300 rounded px-2 py-1 text-sm">
                </div>
            </div>
            <div class="px-4 py-3 border-t border-gray-200 flex justify-end bg-gray-50">
                <button class="px-3 py-1 bg-blue-600 text-white text-sm rounded hover:bg-blue-700" x-on:click="open = false">Done</button>
            </div>
        </div>
    </div>

    {{-- ======================================================================
         GENERATED CODE MODAL
    ====================================================================== --}}
    @if($showCode)
        <div
            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
            x-data
            x-on:click.self="$wire.closeCode()"
        >
            <div class="bg-white rounded-lg shadow-md w-3/4 max-w-3xl flex flex-col" style="max-height: 80vh">
                <div class="px-4 py-3 border-b border-gray-200 flex items-center justify-between bg-gray-50">
                    <span class="text-sm font-semibold text-gray-700">Generated Blade Template</span>
                    <div class="flex items-center gap-2">
                        <button
                            class="text-xs px-3 py-1 bg-gray-200 hover:bg-gray-300 rounded text-gray-700"
                            x-data
                            x-on:click="
                                navigator.clipboard.writeText($refs.codeOutput.value);
                                $el.textContent = 'Copied!';
                                setTimeout(() => $el.textContent = 'Copy', 1500);
                            "
                        >Copy</button>
                        <button class="text-gray-400 hover:text-gray-600" wire:click="closeCode">✕</button>
                    </div>
                </div>
                <div class="flex-1 overflow-auto p-4">
                    <textarea
                        x-ref="codeOutput"
                        class="w-full h-full font-mono text-xs border border-gray-200 rounded p-3 bg-gray-50 text-gray-800 resize-none"
                        style="min-height: 400px"
                        readonly
                    >{{ $generatedCode }}</textarea>
                </div>
            </div>
        </div>
    @endif

</div>
