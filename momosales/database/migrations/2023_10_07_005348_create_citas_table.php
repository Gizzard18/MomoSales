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
        Schema::create('citas', function (Blueprint $table) {
            $table->id();
            $table->decimal('total',10,2);
            $table->decimal('discount',10,2);
            $table->decimal('generated_points',10,2);
            $table->boolean('remember');
            $table->decimal('tips',10,2)->nullable();
            $table->enum('date_status',['En espera','Generada','Cancelada','Pagada'])->default('Generada');
            $table->foreignId('customer_id')->constrained('clientes')->cascadeOnUpdate();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('citas');
    }
};
