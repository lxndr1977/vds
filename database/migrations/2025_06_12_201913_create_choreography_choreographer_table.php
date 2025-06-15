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
        Schema::create('choreography_choreographer', function (Blueprint $table) {
            $table->id();
            $table->foreignId('choreography_id')->constrained()->onDelete('cascade');
            $table->foreignId('choreographer_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->unique(['choreography_id', 'choreographer_id']); // Evita duplicatas
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('choreography_choreographer');
    }
};
