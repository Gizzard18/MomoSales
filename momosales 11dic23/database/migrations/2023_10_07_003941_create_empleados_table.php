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
        Schema::create('empleados', function (Blueprint $table) {
            $table->id();
            $table->string('first_name',80);
            $table->string('last_name',80)->nullable();
            $table->string('email',80)->unique()->nullable();
            $table->string('phone_number',15)->nullable()->unique();
            $table->date('birth_date')->nullable();
            $table->boolean('is_active')->nullable()->default(true);
            $table->string('reward_card',80)->nullable();
            $table->decimal('comissions',20,2)->nullable();
            $table->foreignId('user_id')->nullable()->unique()->constrained('Users');
            $table->rememberToken();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empleados');
    }
};
