{!! '<?xml version="1.0" encoding="UTF-8"?>' !!}
<rss version="2.0" xmlns:g="http://base.google.com/ns/1.0">
    <channel>
        <title>HillNest</title>
        <link>{{ url('/') }}</link>
        <description>Pure A2 bilona ghee from Upper Shimla</description>
@foreach($products as $product)
        <item>
            <g:id>{{ $product->id }}</g:id>
            <g:title>{{ $product->name }}</g:title>
            <g:description>{{ Str::limit(trim($product->description ?: $product->short_description), 4900) }}</g:description>
            <g:link>{{ route('shop.show', $product) }}</g:link>
            <g:image_link>{{ $product->image_url }}</g:image_link>
            <g:availability>{{ $product->stock > 0 ? 'in_stock' : 'out_of_stock' }}</g:availability>
            <g:price>{{ number_format((float) $product->price, 2, '.', '') }} INR</g:price>
            <g:brand>HillNest</g:brand>
            <g:condition>new</g:condition>
            <g:identifier_exists>no</g:identifier_exists>
            <g:product_type>Ghee &gt; A2 Bilona Ghee</g:product_type>
@if($product->net_quantity)
            <g:size>{{ $product->net_quantity }}</g:size>
@endif
        </item>
@endforeach
    </channel>
</rss>
