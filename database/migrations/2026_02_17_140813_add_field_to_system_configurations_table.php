<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('system_configurations', function (Blueprint $table) {
            //
            $table->string('notification_sender_email')->nullable();
            $table->string('notification_cc_email')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('system_configurations', function (Blueprint $table) {
            //
            $table->dropColumn('notification_sender_email');
            $table->dropColumn('notification_cc_email');
        });
    }
};
