<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('marketing_unsubscribed_at')->nullable()->after('is_blocked');
        });

        Schema::create('email_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('subject', 200);
            $table->string('preheader', 200)->nullable();
            $table->string('heading', 200);
            $table->text('body');
            $table->foreignId('coupon_id')->nullable()->constrained()->nullOnDelete();
            $table->string('button_label', 60)->nullable();
            $table->string('button_url', 500)->nullable();
            $table->timestamps();
        });

        Schema::create('email_campaigns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('email_template_id')->nullable()->constrained()->nullOnDelete();
            $table->string('template_name', 100);
            $table->string('subject', 200);
            // Snapshot of the template at send time, so later template edits don't change what this campaign sends.
            $table->json('content');
            $table->string('audience', 20);
            $table->unsignedInteger('total_recipients')->default(0);
            $table->unsignedInteger('sent_count')->default(0);
            $table->unsignedInteger('failed_count')->default(0);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('email_campaign_recipients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('email_campaign_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('email');
            $table->string('status', 20)->default('pending');
            $table->text('error')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();

            $table->index(['email_campaign_id', 'status']);
        });

        $now = now();
        $diwaliCouponId = DB::table('coupons')->where('code', 'DIWALI15')->value('id');

        DB::table('email_templates')->insert([
            [
                'name' => 'Diwali Offer',
                'subject' => '🪔 Diwali Special: {discount} off pure A2 Bilona Ghee',
                'preheader' => 'Use code {coupon_code} at checkout. Valid for a limited time.',
                'heading' => 'Happy Diwali, {first_name}!',
                'body' => "Celebrate this Diwali with the golden goodness of pure A2 bilona ghee, hand-churned in the village homes of Upper Shimla.\n\nThis festive season, enjoy **{discount} off** your order with the code below. Perfect for festive sweets, puja, and gifting to your loved ones.\n\nHurry, the offer is valid for a limited time only.",
                'coupon_id' => $diwaliCouponId,
                'button_label' => 'Shop the Diwali offer',
                'button_url' => '/shop',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Discount Offer',
                'subject' => 'A special treat for you, {first_name}',
                'preheader' => 'Pure A2 bilona ghee from Upper Shimla, now at a special price.',
                'heading' => 'A little something from the hills',
                'body' => "Thank you for being part of the HillNest family, {first_name}.\n\nOur small-batch A2 bilona ghee is slow-cooked over wood fire and packed by hand in our village. Here's a special discount on your next order.",
                'coupon_id' => null,
                'button_label' => 'Shop now',
                'button_url' => '/shop',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('email_campaign_recipients');
        Schema::dropIfExists('email_campaigns');
        Schema::dropIfExists('email_templates');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('marketing_unsubscribed_at');
        });
    }
};
