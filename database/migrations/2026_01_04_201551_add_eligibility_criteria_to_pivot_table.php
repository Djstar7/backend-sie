<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Etape 1: Ajouter les nouvelles colonnes (sans toucher a la cle primaire)
        Schema::table('country_visa_type_required_document', function (Blueprint $table) {
            $table->uuid('id')->nullable()->first();
            $table->string('status_mat')->nullable()->after('required_document_id');
            $table->integer('min_age')->nullable()->after('status_mat');
            $table->integer('max_age')->nullable()->after('min_age');
        });

        // Etape 2: Generer des UUIDs pour les enregistrements existants
        DB::table('country_visa_type_required_document')->get()->each(function ($record) {
            DB::table('country_visa_type_required_document')
                ->where('country_visa_type_id', $record->country_visa_type_id)
                ->where('required_document_id', $record->required_document_id)
                ->update(['id' => (string) \Illuminate\Support\Str::uuid()]);
        });

        // Etape 3: Migrer les donnees existantes depuis required_documents
        $documents = DB::table('required_documents')->get();
        foreach ($documents as $doc) {
            DB::table('country_visa_type_required_document')
                ->where('required_document_id', $doc->id)
                ->update([
                    'status_mat' => $doc->status_mat ?? null,
                    'min_age' => $doc->min_age ?? null,
                    'max_age' => $doc->max_age ?? null,
                ]);
        }

        // Etape 4: Ajouter un index unique sur id
        Schema::table('country_visa_type_required_document', function (Blueprint $table) {
            $table->unique('id', 'unique_pivot_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('country_visa_type_required_document', function (Blueprint $table) {
            $table->dropUnique('unique_pivot_id');
            $table->dropColumn(['id', 'status_mat', 'min_age', 'max_age']);
        });
    }
};
