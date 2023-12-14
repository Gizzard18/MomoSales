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
        Schema::create('metodo_pago_servicios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained('citas')->cascadeOnUpdate();
            $table->foreignId('payment_method_id')->constrained('metodo_pagos')->cascadeOnUpdate();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('metodo_pago_servicios');
    }
};
