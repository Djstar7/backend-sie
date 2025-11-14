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
        Schema::create('profils', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained('users')->onDelete('cascade')->unique();;
            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone');
            $table->enum('gender', ['male', 'female']);
            $table->string('date_of_birth');
            $table->string('place_of_birth');
            $table->enum('status_mat', ['single', 'married', 'divorced', 'widowed']);
            $table->foreignUuid('country_id')
                ->constrained('countrys')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profils');
    }
};
