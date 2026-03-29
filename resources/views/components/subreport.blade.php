<div
    @style([
        'break-before: page' => $breakBefore,
        'break-after: page' => $breakAfter,
    ])
>
    @include($view, $data)
</div>