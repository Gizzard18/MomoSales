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
        Schema::create('venta_asignaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained('ventas')->cascadeOnUpdate();
            $table->foreignId('asignacion_id')->constrained('asignacion_ventas')->cascadeOnUpdate();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('venta_asignaciones');
    }
};
