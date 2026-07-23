<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@hillnest.in'],
            [
                'name' => 'Hillnest Admin',
                'phone' => '9876543210',
                'password' => Hash::make('admin123'),
                'is_admin' => true,
            ]
        );

        $products = [
            [
                'name' => 'Hill Nest Pure Bilona Ghee — 250gm',
                'slug' => 'hill-nest-pure-bilona-ghee-250gm',
                'short_description' => 'Pure • Organic • Himalayan ghee from upper Shimla.',
                'description' => 'Our signature pure bilona cow ghee is slow-cooked from fresh curd using the traditional wooden churn method. Sourced from free-grazing cows in upper Shimla, every jar carries the warmth of the Himalayas — rich aroma, golden colour, and authentic taste your family deserves.',
                'price' => 499,
                'compare_price' => 549,
                'size' => '250gm',
                'image' => 'images/250-gm.png',
                'reviews_count' => 428,
                'badge' => 'New',
                'is_featured' => true,
                'is_bestseller' => false,
                'is_trending' => false,
                'sort_order' => 1,
            ],
            [
                'name' => 'Hill Nest Pure Bilona Ghee — 500gm',
                'slug' => 'hill-nest-pure-bilona-ghee-500gm',
                'short_description' => 'Best seller. Traditional bilona, upper Shimla purity.',
                'description' => 'The perfect family size. Hill Nest bilona ghee is prepared in small batches without shortcuts — no additives, no palm oil, no compromise. Ideal for daily cooking, parathas, dal, and festive sweets.',
                'price' => 899,
                'compare_price' => 999,
                'size' => '500gm',
                'image' => 'images/500-gm.png',
                'reviews_count' => 1847,
                'badge' => null,
                'is_featured' => true,
                'is_bestseller' => true,
                'is_trending' => false,
                'sort_order' => 2,
            ],
            [
                'name' => 'Hill Nest Pure Bilona Ghee — 1kg',
                'slug' => 'hill-nest-pure-bilona-ghee-1kg',
                'short_description' => 'Value pack for households who love pure ghee.',
                'description' => 'One kilogram of Himalayan goodness. Loved by families across India for its grainy texture and nutty finish. Store in a cool, dry place — stays fresh for months when handled with care.',
                'price' => 1699,
                'compare_price' => 1899,
                'size' => '1kg',
                'image' => 'images/1-kg.png',
                'reviews_count' => 962,
                'badge' => null,
                'is_featured' => true,
                'is_bestseller' => false,
                'is_trending' => true,
                'sort_order' => 3,
            ],
        ];

        $slugs = collect($products)->pluck('slug');

        foreach ($products as $data) {
            Product::updateOrCreate(
                ['slug' => $data['slug']],
                array_merge($data, [
                    'category' => 'ghee',
                    'stock' => 150,
                    'is_active' => true,
                ])
            );
        }

        Product::whereNotIn('slug', $slugs)->update(['is_active' => false]);
    }
}
