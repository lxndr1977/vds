<?php

use App\Models\MemberType;
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
        Schema::create('member_fees', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(MemberType::class)->constrained();
            $table->decimal('amount', 8, 2);
            $table->date('valid_until');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_fees');
    }
};
