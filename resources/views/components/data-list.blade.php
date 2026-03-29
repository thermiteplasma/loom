@if($visible)
<div {{ $attributes->merge(['class' => $classes(), 'style' => $styles()]) }}
    @style([
        'display: flex; flex-wrap: wrap;' => $printOrder === 'horizontal',
    ])
>
    @if($view)
        @foreach($items as $item)
            @include($view, ['item' => $item, 'loop' => $loop])
        @endforeach
    @else
        {{ $slot }}
    @endif
</div>
@endif