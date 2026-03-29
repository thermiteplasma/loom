<span style="{{ $styles() }}" {{ $attributes }}>
    {{- $prefix }}<span class="page-number"></span>
    @if($showTotal){{ $separator }}<span class="total-pages"></span>@endif
</span>