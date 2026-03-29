<table
    style="{{ $styles() }}"
    @class(['striped' => $striped])
    {{ $attributes }}
>
    @isset($header)
        <thead>{{ $header }}</thead>
    @endisset

    <tbody>{{ $slot }}</tbody>

    @isset($footer)
        <tfoot>{{ $footer }}</tfoot>
    @endisset
</table>