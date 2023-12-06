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
        Schema::create('asignacion_servicios', function (Blueprint $table) {
            $table->id();
            $table->enum('iva',['16%','%8','%0','Exento'])->default('16%');
            $table->foreignId('empleado_id')->constrained('empleados')->cascadeOnUpdate();
            $table->foreignId('selected_services_id')->constrained('servicios')->cascadeOnUpdate();
            $table->decimal('commission',10,2);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asignacion_servicios');
    }
};
