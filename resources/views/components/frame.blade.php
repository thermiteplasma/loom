@if($visible)
<div style="{{ $styles() }}" {{ $attributes }}>{{ $slot }}</div>
@endif