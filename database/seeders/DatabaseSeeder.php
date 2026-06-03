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
                'name' => 'Pure Bilona Cow Ghee — 250g',
                'slug' => 'pure-bilona-cow-ghee-250g',
                'short_description' => 'Hand-churned bilona ghee from upper Shimla cows.',
                'description' => 'Our signature pure bilona cow ghee is slow-cooked from fresh curd using the traditional wooden churn method. Sourced from free-grazing cows in upper Shimla, every jar carries the warmth of the Himalayas — rich aroma, golden colour, and authentic taste your family deserves.',
                'price' => 499,
                'compare_price' => 549,
                'size' => '250g',
                'image' => 'https://images.unsplash.com/photo-1615485926563-27382494f159?w=800&q=80&auto=format&fit=crop',
                'reviews_count' => 428,
                'badge' => '🎉 New',
                'is_featured' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Pure Bilona Cow Ghee — 500g',
                'slug' => 'pure-bilona-cow-ghee-500g',
                'short_description' => 'Best seller. Traditional bilona, upper Shimla purity.',
                'description' => 'The perfect family size. Hillnest bilona ghee is prepared in small batches without shortcuts — no additives, no palm oil, no compromise. Ideal for daily cooking, parathas, dal, and festive sweets.',
                'price' => 899,
                'compare_price' => 999,
                'size' => '500g',
                'image' => 'https://images.unsplash.com/photo-1628088062856-eee32a9352e2?w=800&q=80&auto=format&fit=crop',
                'reviews_count' => 1847,
                'badge' => '🔥 Best Seller',
                'is_featured' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Pure Bilona Cow Ghee — 1kg',
                'slug' => 'pure-bilona-cow-ghee-1kg',
                'short_description' => 'Value pack for households who love pure ghee.',
                'description' => 'One kilogram of Himalayan goodness. Loved by families across India for its grainy texture and nutty finish. Store in a cool, dry place — stays fresh for months when handled with care.',
                'price' => 1699,
                'compare_price' => 1899,
                'size' => '1kg',
                'image' => 'https://images.unsplash.com/photo-1589985278721-4afeaa8bce84?w=800&q=80&auto=format&fit=crop',
                'reviews_count' => 962,
                'badge' => '🚀 Trending',
                'is_featured' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Pure Bilona Cow Ghee — 2kg',
                'slug' => 'pure-bilona-cow-ghee-2kg',
                'short_description' => 'Bulk pack. Same bilona process, maximum savings.',
                'description' => 'For gifting, bulk kitchens, or ghee lovers who never run out. Every 2kg tin is filled after strict quality checks at our Shimla facility. Free shipping on orders above ₹2000.',
                'price' => 3199,
                'compare_price' => 3599,
                'size' => '2kg',
                'image' => 'https://images.unsplash.com/photo-1606923829579-195b305bdac6?w=800&q=80&auto=format&fit=crop',
                'reviews_count' => 534,
                'badge' => 'Value Pack',
                'is_featured' => false,
                'sort_order' => 4,
            ],
        ];

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
    }
}
