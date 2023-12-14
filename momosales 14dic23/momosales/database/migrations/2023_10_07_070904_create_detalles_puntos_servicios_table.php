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
        Schema::create('detalles_puntos_servicios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('generated_points')->constrained('asignacion_servicios')->cascadeOnUpdate();
            $table->foreignId('customer_id')->constrained('clientes')->cascadeOnUpdate();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalles_puntos_servicios');
    }
};
