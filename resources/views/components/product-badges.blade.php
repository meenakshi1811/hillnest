@props(['product', 'class' => 'product-card-rosier__badges'])

@if($product->display_badges)
<div class="{{ $class }}">
    @foreach($product->display_badges as $badge)
        <span class="product-card-rosier__badge product-card-rosier__badge--{{ $badge['variant'] }}">{{ $badge['label'] }}</span>
    @endforeach
</div>
@endif
