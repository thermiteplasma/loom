<div class="mb-4">
    <x-loom-designer-prop-section label="Borders" />
    <div class="grid grid-cols-2 gap-x-2 mt-1">
        <x-loom-designer-checkbox label="All" prop="border" />
        <x-loom-designer-checkbox label="Top" prop="borderTop" />
        <x-loom-designer-checkbox label="Bottom" prop="borderBottom" />
        <x-loom-designer-checkbox label="Left" prop="borderLeft" />
        <x-loom-designer-checkbox label="Right" prop="borderRight" />
    </div>
    <x-loom-designer-select label="Style" prop="borderStyle" :options="[''=>'—','solid'=>'Solid','dashed'=>'Dashed','dotted'=>'Dotted','double'=>'Double']" />
    <x-loom-designer-text label="Width" prop="borderWidth" placeholder="e.g. 0.5pt" />
    <x-loom-designer-color label="Color" prop="borderColor" />
    <x-loom-designer-text label="Radius" prop="borderRadius" placeholder="e.g. 3px" />
</div>
