<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('event_service', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->foreignId('service_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('qty')->default(1);
            $table->decimal('price', 12, 2)->nullable();
            $table->timestamps();

            $table->unique(['event_id', 'service_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_service');
    }
};
