<div class="mb-4">
    <x-loom-designer-prop-section label="Typography" />
    <x-loom-designer-text label="Font Family" prop="fontFamily" placeholder="e.g. DejaVu Sans" />
    <x-loom-designer-text label="Font Size" prop="fontSize" placeholder="e.g. 10pt, 12px" />
    <x-loom-designer-color label="Color" prop="color" />
    <x-loom-designer-select label="Align" prop="align" :options="[''=>'—','left'=>'Left','center'=>'Center','right'=>'Right','justify'=>'Justify']" />
    <x-loom-designer-select label="Vertical Align" prop="verticalAlign" :options="[''=>'—','top'=>'Top','middle'=>'Middle','bottom'=>'Bottom']" />
    <x-loom-designer-select label="Transform" prop="textTransform" :options="[''=>'—','uppercase'=>'Uppercase','lowercase'=>'Lowercase','capitalize'=>'Capitalize']" />
    <x-loom-designer-text label="Line Height" prop="lineHeight" placeholder="e.g. 1.5" />
    <x-loom-designer-text label="Letter Spacing" prop="letterSpacing" placeholder="e.g. 0.5px" />
    <div class="grid grid-cols-2 gap-x-2 mt-1">
        <x-loom-designer-checkbox label="Bold" prop="bold" />
        <x-loom-designer-checkbox label="Italic" prop="italic" />
        <x-loom-designer-checkbox label="Underline" prop="underline" />
        <x-loom-designer-checkbox label="Strikethrough" prop="strikethrough" />
    </div>
</div>
