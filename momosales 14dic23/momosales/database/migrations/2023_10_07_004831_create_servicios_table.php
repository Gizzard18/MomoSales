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
        Schema::create('servicios', function (Blueprint $table) {
            $table->id();
            $table->string('name',80);
            $table->string('description',200)->nullable();
            $table->decimal('gross_price',10,2);
            $table->decimal('disccount_price',10,2)->nullable();
            $table->decimal('reward_points',10,2)->nullable();
            $table->integer('duration')->default('15');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servicios');
    }
};
