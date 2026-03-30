<?php

namespace Thermiteplasma\Loom\Livewire;

use Illuminate\Support\Str;
use Livewire\Component;

class TemplateDesigner extends Component
{
    // -------------------------------------------------------------------------
    // Report-level settings
    // -------------------------------------------------------------------------
    public array $reportProps = [
        'size' => 'A4',
        'orientation' => 'portrait',
        'margin' => '10mm',
        'fontFamily' => null,
    ];

    // -------------------------------------------------------------------------
    // Template state: ordered list of bands, each containing elements
    // -------------------------------------------------------------------------
    public array $bands = [];

    // -------------------------------------------------------------------------
    // Selection state
    // -------------------------------------------------------------------------
    public ?int $selectedBandIndex = null;

    public ?int $selectedElementIndex = null;

    public string $selectedType = ''; // 'band' | 'element'

    // -------------------------------------------------------------------------
    // Flat editing props — synced from the selected element/band
    // wire:model.live is safe here because the path never changes shape
    // -------------------------------------------------------------------------
    public array $editingProps = [];

    // -------------------------------------------------------------------------
    // Output
    // -------------------------------------------------------------------------
    public string $generatedCode = '';

    public bool $showCode = false;

    // =========================================================================
    // Lifecycle
    // =========================================================================

    public function mount(): void
    {
        $this->bands = $this->defaultBands();
    }

    // =========================================================================
    // Selection
    // =========================================================================

    public function selectBand(int $bandIndex): void
    {
        $this->selectedBandIndex = $bandIndex;
        $this->selectedElementIndex = null;
        $this->selectedType = 'band';
        $this->editingProps = $this->bands[$bandIndex]['props'];
    }

    public function selectElement(int $bandIndex, int $elementIndex): void
    {
        $this->selectedBandIndex = $bandIndex;
        $this->selectedElementIndex = $elementIndex;
        $this->selectedType = 'element';
        $this->editingProps = $this->bands[$bandIndex]['elements'][$elementIndex]['props'];
    }

    public function deselect(): void
    {
        $this->selectedBandIndex = null;
        $this->selectedElementIndex = null;
        $this->selectedType = '';
        $this->editingProps = [];
    }

    // =========================================================================
    // Editing props sync — called automatically by Livewire when
    // wire:model.live="editingProps.someKey" changes
    // =========================================================================

    public function updatedEditingProps(): void
    {
        if ($this->selectedType === 'element'
            && $this->selectedBandIndex !== null
            && $this->selectedElementIndex !== null
        ) {
            $this->bands[$this->selectedBandIndex]['elements'][$this->selectedElementIndex]['props']
                = $this->editingProps;
        }

        if ($this->selectedType === 'band' && $this->selectedBandIndex !== null) {
            $this->bands[$this->selectedBandIndex]['props'] = $this->editingProps;
        }
    }

    // =========================================================================
    // Element management
    // =========================================================================

    public function addElement(int $bandIndex, string $type): void
    {
        $element = [
            'id' => uniqid('el_'),
            'type' => $type,
            'props' => $this->defaultPropsFor($type),
        ];

        $this->bands[$bandIndex]['elements'][] = $element;

        $newIndex = count($this->bands[$bandIndex]['elements']) - 1;
        $this->selectElement($bandIndex, $newIndex);
    }

    public function removeElement(int $bandIndex, int $elementIndex): void
    {
        array_splice($this->bands[$bandIndex]['elements'], $elementIndex, 1);

        if ($this->selectedBandIndex === $bandIndex
            && $this->selectedElementIndex === $elementIndex
        ) {
            $this->deselect();
        }
    }

    public function moveElementUp(int $bandIndex, int $elementIndex): void
    {
        if ($elementIndex === 0) {
            return;
        }

        $elements = $this->bands[$bandIndex]['elements'];
        [$elements[$elementIndex - 1], $elements[$elementIndex]] =
            [$elements[$elementIndex], $elements[$elementIndex - 1]];
        $this->bands[$bandIndex]['elements'] = $elements;

        if ($this->selectedBandIndex === $bandIndex
            && $this->selectedElementIndex === $elementIndex
        ) {
            $this->selectedElementIndex = $elementIndex - 1;
        }
    }

    public function moveElementDown(int $bandIndex, int $elementIndex): void
    {
        $count = count($this->bands[$bandIndex]['elements']);

        if ($elementIndex >= $count - 1) {
            return;
        }

        $elements = $this->bands[$bandIndex]['elements'];
        [$elements[$elementIndex], $elements[$elementIndex + 1]] =
            [$elements[$elementIndex + 1], $elements[$elementIndex]];
        $this->bands[$bandIndex]['elements'] = $elements;

        if ($this->selectedBandIndex === $bandIndex
            && $this->selectedElementIndex === $elementIndex
        ) {
            $this->selectedElementIndex = $elementIndex + 1;
        }
    }

    // =========================================================================
    // Band management
    // =========================================================================

    public function toggleBandCollapsed(int $bandIndex): void
    {
        $this->bands[$bandIndex]['collapsed'] = ! ($this->bands[$bandIndex]['collapsed'] ?? false);
    }

    // =========================================================================
    // Code generation
    // =========================================================================

    public function generate(): void
    {
        $lines = [];

        $reportAttrs = $this->renderProps($this->reportProps, bandLevel: false);
        $lines[] = "<x-loom-report {$reportAttrs}>";
        $lines[] = '';

        foreach ($this->bands as $band) {
            $hasElements = count($band['elements']) > 0;

            if (! $hasElements && empty(array_filter($band['props']))) {
                continue;
            }

            $tag = 'x-loom-'.$band['type'];
            $bandAttrs = $this->renderProps($band['props'], bandLevel: true);
            $open = $bandAttrs ? "<{$tag} {$bandAttrs}>" : "<{$tag}>";

            if (! $hasElements) {
                $lines[] = "    {$open}</{$tag}>";
                $lines[] = '';

                continue;
            }

            $lines[] = "    {$open}";

            foreach ($band['elements'] as $element) {
                $lines[] = $this->renderElement($element, indent: 8);
            }

            $lines[] = "    </{$tag}>";
            $lines[] = '';
        }

        $lines[] = '</x-loom-report>';

        $this->generatedCode = implode("\n", $lines);
        $this->showCode = true;
    }

    public function closeCode(): void
    {
        $this->showCode = false;
    }

    // =========================================================================
    // Helpers
    // =========================================================================

    private function renderElement(array $element, int $indent): string
    {
        $pad = str_repeat(' ', $indent);
        $tag = 'x-loom-'.$element['type'];
        $props = $element['props'];

        // Content goes in the slot, not as an attribute
        $content = $props['content'] ?? '';
        unset($props['content']);

        $attrs = $this->renderProps($props, bandLevel: false);
        $open = $attrs ? "<{$tag} {$attrs}>" : "<{$tag}>";

        if ($content !== '') {
            return "{$pad}{$open}{$content}</{$tag}>";
        }

        return "{$pad}{$open}</{$tag}>";
    }

    private function renderProps(array $props, bool $bandLevel): string
    {
        $parts = [];

        foreach ($props as $key => $value) {
            if ($value === null || $value === '' || $value === false) {
                continue;
            }

            if ($value === true) {
                $parts[] = $key; // bare boolean attribute

                continue;
            }

            $escaped = htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
            $parts[] = "{$key}=\"{$escaped}\"";
        }

        return implode(' ', $parts);
    }

    // =========================================================================
    // Defaults
    // =========================================================================

    private function defaultBands(): array
    {
        return [
            $this->makeBand('title', 'Title'),
            $this->makeBand('page-header', 'Page Header'),
            $this->makeBand('column-header', 'Column Header'),
            $this->makeBand('detail', 'Detail'),
            $this->makeBand('column-footer', 'Column Footer'),
            $this->makeBand('page-footer', 'Page Footer'),
            $this->makeBand('summary', 'Summary'),
        ];
    }

    private function makeBand(string $type, string $label): array
    {
        return [
            'type' => $type,
            'label' => $label,
            'collapsed' => false,
            'props' => [
                'background' => null,
                'padding' => null,
                'borderBottom' => false,
                'borderTop' => false,
                'borderColor' => null,
                'minHeight' => null,
            ],
            'elements' => [],
        ];
    }

    private function defaultPropsFor(string $type): array
    {
        $base = [
            'content' => '',
            // Typography
            'fontFamily' => null,
            'fontSize' => null,
            'color' => null,
            'bold' => false,
            'italic' => false,
            'underline' => false,
            'strikethrough' => false,
            'align' => null,
            'verticalAlign' => null,
            'lineHeight' => null,
            'textTransform' => null,
            // Box model
            'width' => null,
            'height' => null,
            'padding' => null,
            'margin' => null,
            // Borders
            'border' => false,
            'borderTop' => false,
            'borderBottom' => false,
            'borderLeft' => false,
            'borderRight' => false,
            'borderStyle' => null,
            'borderWidth' => null,
            'borderColor' => null,
            'borderRadius' => null,
            // Background
            'background' => null,
            'opacity' => null,
        ];

        return match ($type) {
            'static-text' => array_merge($base, ['content' => 'Label']),
            'field' => array_merge($base, ['content' => '$record->field']),
            'page-number' => ['showTotal' => false],
            'total-pages' => [],
            'current-date' => ['format' => null],
            'line' => ['width' => '100%', 'borderBottom' => true, 'borderColor' => '#cccccc'],
            'rectangle' => array_merge($base, ['width' => '20mm', 'height' => '10mm', 'background' => '#e0e0e0']),
            'image' => ['src' => '', 'width' => '30mm', 'height' => '20mm', 'alt' => ''],
            'row' => ['justify' => null, 'items' => null, 'gap' => null],
            'column' => array_merge($base, ['flex' => null]),
            'frame' => array_merge($base, []),
            default => $base,
        };
    }

    public function elementLabel(string $type): string
    {
        return match ($type) {
            'static-text' => 'Static Text',
            'field' => 'Field',
            'page-number' => 'Page Number',
            'total-pages' => 'Total Pages',
            'current-date' => 'Current Date',
            'line' => 'Line',
            'rectangle' => 'Rectangle',
            'ellipse' => 'Ellipse',
            'image' => 'Image',
            'row' => 'Row',
            'column' => 'Column',
            'frame' => 'Frame',
            'table' => 'Table',
            'subreport' => 'Subreport',
            'data-list' => 'Data List',
            default => Str::title(str_replace('-', ' ', $type)),
        };
    }

    public function elementIcon(string $type): string
    {
        return match ($type) {
            'static-text' => 'T',
            'field' => '{}',
            'page-number' => '#',
            'total-pages' => '##',
            'current-date' => '📅',
            'line' => '—',
            'rectangle' => '▭',
            'ellipse' => '◯',
            'image' => '🖼',
            'row' => '☰',
            'column' => '▏',
            'frame' => '⬜',
            'table' => '⊞',
            default => '·',
        };
    }

    // =========================================================================
    // Render
    // =========================================================================

    public function render()
    {
        return view('loom::livewire.template-designer')
            ->layout('loom::layouts.designer');
    }
}
