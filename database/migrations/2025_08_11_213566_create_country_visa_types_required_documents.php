<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('country_visa_type_required_document', function (Blueprint $table) {
            $table->foreignUuid('country_visa_type_id')
                ->constrained('country_visa_types')
                ->cascadeOnDelete();

            $table->foreignUuid('required_document_id')
                ->constrained('required_documents')
                ->cascadeOnDelete();

            $table->primary(['country_visa_type_id', 'required_document_id']); // clé primaire composite

            $table->timestamps(); // facultatif, si tu veux savoir quand la relation a été créée
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('country_visa_type_required_document');
    }
};
