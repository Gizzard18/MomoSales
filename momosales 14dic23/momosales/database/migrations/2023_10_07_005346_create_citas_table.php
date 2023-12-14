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
            $table->string('title',200)->nullable()->default(null);
            $table->string('url',200)->nullable()->default(null);
            $table->dateTime('start')->nullable()->default(null);
            $table->dateTime('end')->nullable()->default(null);
            $table->decimal('tips',10,2)->nullable();
            $table->enum('date_status',['Generada','Cancelada','Pagada'])->default('Generada');
            $table->foreignId('customer_id')->constrained('clientes')->cascadeOnUpdate();
            $table->foreignId('user_id')->constrained('users')->cascadeOnUpdate();
            $table->decimal('total',10,2);
            $table->decimal('discount',10,2);
            $table->boolean('remember');
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
