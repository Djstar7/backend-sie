<?php

use App\Models\User;
use App\Models\VisaType;
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
        Schema::create('visa_requests', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->foreignUuid('visa_type_id')
                ->constrained('visa_types')
                ->cascadeOnDelete();
            $table->foreignUuid('origin_country_id')
                ->constrained('countrys')
                ->cascadeOnDelete();
            $table->foreignUuid('destination_country_id')
                ->constrained('countrys')
                ->cascadeOnDelete();
            $table->enum('status', ['created', 'pending', 'processing', 'approved', 'rejected'])
                ->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visa_requests');
    }
};
