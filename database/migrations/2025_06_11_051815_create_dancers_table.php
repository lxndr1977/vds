<?php

use App\Models\School;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('dancers', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(School::class)->constrained();
            $table->string('name');
            $table->string('birth_date', 9)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dancers');
    }
};
