<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Ajouter les criteres d'eligibilite directement dans country_visa_types
     * Chaque combinaison (country, visa_type, status_mat, min_age, max_age) = 1 eligibilite
     */
    public function up(): void
    {
        // Ajouter les colonnes
        Schema::table('country_visa_types', function (Blueprint $table) {
            $table->string('status_mat')->nullable()->after('processing_duration_max');
            $table->integer('min_age')->nullable()->after('status_mat');
            $table->integer('max_age')->nullable()->after('min_age');
        });

        // Migrer les donnees depuis le pivot vers country_visa_types
        $pivotRecords = DB::table('country_visa_type_required_document')
            ->select('country_visa_type_id', 'status_mat', 'min_age', 'max_age')
            ->distinct()
            ->get();

        foreach ($pivotRecords as $pivot) {
            $existingCvt = DB::table('country_visa_types')
                ->where('id', $pivot->country_visa_type_id)
                ->first();

            if ($existingCvt) {
                // Si c'est le premier, mettre a jour directement
                $alreadyHasCriteria = !is_null($existingCvt->status_mat);

                if (!$alreadyHasCriteria) {
                    DB::table('country_visa_types')
                        ->where('id', $pivot->country_visa_type_id)
                        ->update([
                            'status_mat' => $pivot->status_mat,
                            'min_age' => $pivot->min_age,
                            'max_age' => $pivot->max_age,
                        ]);
                } else {
                    // Creer une nouvelle eligibilite
                    $newId = (string) \Illuminate\Support\Str::uuid();
                    DB::table('country_visa_types')->insert([
                        'id' => $newId,
                        'country_id' => $existingCvt->country_id,
                        'visa_type_id' => $existingCvt->visa_type_id,
                        'price_base' => $existingCvt->price_base,
                        'price_per_child' => $existingCvt->price_per_child,
                        'processing_duration_min' => $existingCvt->processing_duration_min,
                        'processing_duration_max' => $existingCvt->processing_duration_max,
                        'status_mat' => $pivot->status_mat,
                        'min_age' => $pivot->min_age,
                        'max_age' => $pivot->max_age,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    // Migrer les documents du pivot vers le nouveau CVT
                    DB::table('country_visa_type_required_document')
                        ->where('country_visa_type_id', $pivot->country_visa_type_id)
                        ->where('status_mat', $pivot->status_mat)
                        ->where('min_age', $pivot->min_age)
                        ->where('max_age', $pivot->max_age)
                        ->update(['country_visa_type_id' => $newId]);
                }
            }
        }

        // Ajouter la contrainte d'unicite
        Schema::table('country_visa_types', function (Blueprint $table) {
            $table->unique(
                ['country_id', 'visa_type_id', 'status_mat', 'min_age', 'max_age'],
                'unique_eligibility'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('country_visa_types', function (Blueprint $table) {
            $table->dropUnique('unique_eligibility');
            $table->dropColumn(['status_mat', 'min_age', 'max_age']);
        });
    }
};
