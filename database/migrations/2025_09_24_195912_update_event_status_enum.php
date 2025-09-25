<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateEventStatusEnum extends Migration
{
    public function up()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->enum('status', [
                'requested',
                'approved',
                'scheduled',
                'completed',
                'rejected',
                'request_meeting',
                'meeting',
            ])->default('requested')->change();
        });
    }

    public function down()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->enum('status', [
                'requested',
                'approved',
                'scheduled',
                'completed',
                'rejected',
                'request_meeting',
            ])->default('requested')->change();
        });
    }
}
