<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Modifier la cle primaire pour permettre plusieurs status_mat par document
     */
    public function up(): void
    {
        // Sauvegarder les donnees existantes
        $existingData = DB::table('country_visa_type_required_document')->get();

        // Supprimer les contraintes de cle etrangere
        Schema::table('country_visa_type_required_document', function (Blueprint $table) {
            $table->dropForeign(['country_visa_type_id']);
            $table->dropForeign(['required_document_id']);
        });

        // Supprimer la table et la recreer avec la bonne structure
        Schema::dropIfExists('country_visa_type_required_document');

        Schema::create('country_visa_type_required_document', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('country_visa_type_id')->constrained('country_visa_types')->cascadeOnDelete();
            $table->foreignUuid('required_document_id')->constrained('required_documents')->cascadeOnDelete();
            $table->string('status_mat')->nullable();
            $table->integer('min_age')->nullable();
            $table->integer('max_age')->nullable();
            $table->timestamps();

            // Index unique sur la combinaison complete des criteres
            $table->unique(
                ['country_visa_type_id', 'required_document_id', 'status_mat', 'min_age', 'max_age'],
                'unique_eligibility_criteria'
            );
        });

        // Restaurer les donnees avec des UUIDs
        foreach ($existingData as $row) {
            DB::table('country_visa_type_required_document')->insert([
                'id' => $row->id ?? (string) \Illuminate\Support\Str::uuid(),
                'country_visa_type_id' => $row->country_visa_type_id,
                'required_document_id' => $row->required_document_id,
                'status_mat' => $row->status_mat,
                'min_age' => $row->min_age,
                'max_age' => $row->max_age,
                'created_at' => $row->created_at ?? now(),
                'updated_at' => $row->updated_at ?? now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Non reversible
    }
};
