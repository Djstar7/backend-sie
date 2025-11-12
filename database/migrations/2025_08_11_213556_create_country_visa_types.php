<?php

use App\Models\Country;
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
        Schema::create('country_visa_types', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('country_id')
                ->constrained('countrys')
                ->cascadeOnDelete();
            $table->foreignUuid('visa_type_id')
                ->constrained('visa_types')
                ->cascadeOnDelete();
            $table->decimal('price_base', 10, 2);
            $table->decimal('price_per_child', 10, 2)->nullable();
            $table->integer('processing_duration_min');
            $table->integer('processing_duration_max');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('country_visa_type');
    }
};
