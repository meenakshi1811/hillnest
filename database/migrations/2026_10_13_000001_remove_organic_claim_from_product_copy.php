<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * "Organic" is a certified claim in India, so drop it from the seeded product copy.
     */
    public function up(): void
    {
        DB::update(
            'UPDATE products SET short_description = REPLACE(short_description, ?, ?) WHERE short_description LIKE ?',
            ['Pure • Organic • ', 'Pure • A2 • ', '%Pure • Organic • %'],
        );
    }

    public function down(): void
    {
        //
    }
};
