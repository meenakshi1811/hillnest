<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('last_seen_at')->nullable()->after('marketing_unsubscribed_at');
        });

        // One row per visit: a new visit starts after 30 minutes without activity.
        Schema::create('site_visits', function (Blueprint $table) {
            $table->id();
            $table->uuid('visitor_id')->index();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('ip_address', 45)->nullable()->index();
            $table->string('device', 20)->nullable();
            $table->string('browser', 40)->nullable();
            $table->string('source', 40)->nullable();
            $table->string('landing_path', 255)->nullable();
            $table->string('referrer', 500)->nullable();
            $table->unsignedInteger('page_views')->default(0);
            $table->unsignedInteger('cart_adds')->default(0);
            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamp('last_seen_at')->nullable();
            $table->timestamps();

            $table->index('created_at');
        });

        Schema::create('cart_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_visit_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('ip_address', 45)->nullable();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->string('product_name');
            $table->string('action', 20);
            $table->unsignedInteger('quantity');
            $table->decimal('value', 10, 2)->default(0);
            $table->timestamps();

            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cart_events');
        Schema::dropIfExists('site_visits');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('last_seen_at');
        });
    }
};
