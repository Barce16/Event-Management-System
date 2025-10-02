<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            // Add new guests column as text
            $table->text('guests')->nullable()->after('budget');
        });

        // Migrate existing data from guest_count to guests
        DB::statement("UPDATE events SET guests = CONCAT(guest_count, ' guests') WHERE guest_count IS NOT NULL");

        // Drop old guest_count column
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('guest_count');
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->unsignedInteger('guest_count')->nullable()->after('budget');
        });

        // Restore data if rolling back
        DB::statement("UPDATE events SET guest_count = CAST(REGEXP_REPLACE(guests, '[^0-9]', '') AS UNSIGNED) WHERE guests IS NOT NULL");

        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('guests');
        });
    }
};
