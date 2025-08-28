<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('event_service', function (Blueprint $table) {
            if (Schema::hasColumn('event_service', 'quantity')) {
                $table->dropColumn('quantity');
            }
        });
    }

    public function down(): void
    {
        Schema::table('event_service', function (Blueprint $table) {
            $table->unsignedInteger('quantity')->default(1);
        });
    }
};
