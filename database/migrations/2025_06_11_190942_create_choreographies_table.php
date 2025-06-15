<?php

use App\Models\School;
use App\Models\DanceStyle;
use App\Models\ChoreographyType;
use App\Models\ChoreographyCategory;
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
        Schema::create('choreographies', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(School::class)->constrained();
            $table->foreignIdFor(ChoreographyType::class)->constrained();
            $table->foreignIdFor(ChoreographyCategory::class)->constrained();
            $table->foreignIdFor(DanceStyle::class)->constrained();
            $table->string('name');
            $table->string('music');
            $table->string('music_composer');
            $table->string('duration', 8); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('choreographies');
    }
};
