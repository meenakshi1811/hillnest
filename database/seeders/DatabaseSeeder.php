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
                'description' => "Our signature pure bilona cow ghee is slow-cooked from fresh curd using the traditional wooden churn method. Sourced from free-grazing A2 cows in Chhajpur, Upper Shimla, every jar carries the warmth of the Himalayas — rich aroma, golden colour, and authentic taste your family deserves.\n\nThis 250gm jar is ideal for first-time buyers, small households, or anyone who wants to taste true village-made ghee before stocking up. Use it for tadka, drizzle over dal and rice, or enjoy a spoonful in warm milk. No additives, no palm oil, no factory shortcuts — just pure A2 bilona ghee, handcrafted in local homes and sealed with care before it leaves the mountains.",
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
                'description' => "The perfect family size. Hill Nest bilona ghee is prepared in small batches without shortcuts — no additives, no palm oil, no compromise. Ideal for daily cooking, parathas, dal, festive sweets, and everyday tadka that fills the kitchen with a nutty Himalayan aroma.\n\nMade from A2 milk collected from village homes in Upper Shimla, this 500gm jar is our most-loved pack for households who cook with ghee every day. Each batch is hand-churned the traditional bilona way and slow-cooked over wood fire, so you get the grainy texture, golden clarity, and deep flavour that only real village ghee can offer.",
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
                'description' => "One kilogram of Himalayan goodness. Loved by families across India for its grainy texture and nutty finish, this value pack is made for homes that never run out of pure ghee — from daily meals and festival sweets to gifting relatives who appreciate authenticity.\n\nEvery jar begins with fresh A2 milk from free-grazing cows in Chhajpur, Upper Shimla. The curd is set overnight, hand-churned in wooden bilona, and clarified slowly over wood fire before being packed in our village workshop. Stock your pantry with the same purity our founders grew up with — stays fresh for months when stored cool and dry, and always handled with a clean, dry spoon.",
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
