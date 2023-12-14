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
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('first_name',35)->unique();
            $table->string('last_name',35)->nullable();
            $table->date('birth_date')->nullable();
            $table->string('email',65)->nullable();
            $table->string('description',100)->nullable();
            $table->string('phone',15)->unique()->nullable();
            $table->boolean('want_custom_messages')->default(false)->nullable();
            $table->boolean('want_offers')->default(false)->nullable();
            $table->boolean('is_active')->default(true)->nullable();
            $table->integer('platform_id')->nullable()->nullable();
            $table->foreignId('categoria_cliente_id')->nullable()->default(NULL)->constrained('categoria_clientes')->cascadeOnUpdate();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
