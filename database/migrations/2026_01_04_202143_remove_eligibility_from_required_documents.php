<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Les criteres d'eligibilite sont maintenant dans la table pivot
     */
    public function up(): void
    {
        Schema::table('required_documents', function (Blueprint $table) {
            $table->dropColumn(['status_mat', 'min_age', 'max_age']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('required_documents', function (Blueprint $table) {
            $table->string('status_mat')->nullable();
            $table->integer('min_age')->nullable();
            $table->integer('max_age')->nullable();
        });
    }
};
