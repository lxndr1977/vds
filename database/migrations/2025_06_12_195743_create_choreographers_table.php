<?php

use App\Models\School;
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
        Schema::create('choreographers', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(School::class)->constrained();
            $table->string('name');
            $table->boolean('is_attending')->default(false);
            $table->boolean('is_public_domain')->default(false);
            $table->boolean('is_adaptation')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('choreographers');
    }
};
