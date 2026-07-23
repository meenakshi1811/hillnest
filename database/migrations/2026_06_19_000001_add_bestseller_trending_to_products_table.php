<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('is_bestseller')->default(false)->after('is_featured');
            $table->boolean('is_trending')->default(false)->after('is_bestseller');
        });

        DB::table('products')->where('badge', 'Best Seller')->update(['is_bestseller' => true]);
        DB::table('products')->where('badge', 'Trending')->update(['is_trending' => true]);
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['is_bestseller', 'is_trending']);
        });
    }
};
