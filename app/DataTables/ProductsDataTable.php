<?php

namespace App\DataTables;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Yajra\DataTables\Facades\DataTables;

class ProductsDataTable
{
    public function query(): Builder
    {
        return Product::query()->orderBy('sort_order');
    }

    public function json()
    {
        return DataTables::eloquent($this->query())
            ->addColumn('product_cell', function (Product $product) {
                $size = $product->size
                    ? '<p class="admin-table__muted admin-product-cell__size">'.e($product->size).'</p>'
                    : '';

                return '
                    <div class="admin-product-cell">
                        <img src="'.e($product->image_url).'" alt="'.e($product->name).'" class="admin-product-thumb">
                        <div>
                            <p class="admin-product-cell__name">'.e($product->name).'</p>
                            '.$size.'
                        </div>
                    </div>';
            })
            ->addColumn('product_tags', fn (Product $product) => $this->renderTags($product))
            ->addColumn('status_toggle', function (Product $product) {
                $checked = $product->is_active ? 'checked' : '';
                $url = route('admin.products.toggle', $product);

                return '
                    <label class="admin-toggle" title="'.($product->is_active ? 'Active — click to hide' : 'Hidden — click to activate').'">
                        <input type="checkbox" class="admin-toggle__input js-product-toggle" data-url="'.$url.'" '.$checked.'>
                        <span class="admin-toggle__track" aria-hidden="true"></span>
                        <span class="admin-toggle__label">'.($product->is_active ? 'Active' : 'Hidden').'</span>
                    </label>';
            })
            ->addColumn('action', function (Product $product) {
                $viewUrl = route('shop.show', $product);
                $editUrl = route('admin.products.edit', $product);
                $deleteUrl = route('admin.products.destroy', $product);

                return '
                    <div class="admin-row-actions">
                        <a href="'.$viewUrl.'" class="admin-icon-btn" target="_blank" rel="noopener" title="View on storefront" aria-label="View product">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                        </a>
                        <a href="'.$editUrl.'" class="admin-icon-btn" title="Edit product" aria-label="Edit product">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
                        </a>
                        <button type="button" class="admin-icon-btn admin-icon-btn--danger js-product-delete" data-url="'.$deleteUrl.'" data-name="'.e($product->name).'" title="Delete product" aria-label="Delete product">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M3 6h18"/><path d="M8 6V4h8v2"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                        </button>
                    </div>';
            })
            ->editColumn('price', fn (Product $product) => '₹'.number_format($product->price, 0))
            ->rawColumns(['product_cell', 'product_tags', 'status_toggle', 'action'])
            ->toJson();
    }

    private function renderTags(Product $product): string
    {
        $tags = [];

        if ($product->is_bestseller) {
            $tags[] = '<span class="admin-badge admin-badge--bestseller">Best Seller</span>';
        }

        if ($product->is_trending) {
            $tags[] = '<span class="admin-badge admin-badge--trending">Trending</span>';
        }

        if ($product->badge) {
            $tags[] = '<span class="admin-badge admin-badge--gold">'.e($product->badge).'</span>';
        }

        if ($product->is_featured) {
            $tags[] = '<span class="admin-badge admin-badge--featured">Featured</span>';
        }

        if ($tags === []) {
            return '<span class="admin-table__muted">—</span>';
        }

        return '<div class="admin-tag-list">'.implode('', $tags).'</div>';
    }
}
