@if($visible)
<div {{ $attributes->merge(['class' => $classes(), 'style' => $styles()]) }}>
    {{ $slot }}
</div>
@endif