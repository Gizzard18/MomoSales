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
        Schema::create('caja_cortes', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('description',100)->nullable();
            $table->decimal('total',20,2)->nullable;
            $table->decimal('total_cash',20,2)->nullable;
            $table->decimal('total_card',20,2)->nullable;
            $table->decimal('total_NF',20,2)->nullable;
            $table->decimal('withdrawal',20,2);
            $table->decimal('discounts',20,2)->nullable();
            $table->decimal('tips',20,2)->nullable();
            $table->decimal('generated_points',20,2)->nullable();
            $table->decimal('comissions',20,2)->nullable();
            $table->decimal('real_on_cash',20,2);
            $table->decimal('real_on_card',20,2);
            $table->decimal('real_on_NF',20,2);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('caja_cortes');
    }
};
