<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Fusionner les CountryVisaType dupliques (meme country_id + visa_type_id)
     * en transferant tous les documents du pivot vers le premier CVT
     */
    public function up(): void
    {
        // Trouver les groupes de CVT dupliques
        $duplicates = DB::table('country_visa_types')
            ->select('country_id', 'visa_type_id', DB::raw('COUNT(*) as count'), DB::raw('MIN(id) as keep_id'))
            ->groupBy('country_id', 'visa_type_id')
            ->having('count', '>', 1)
            ->get();

        foreach ($duplicates as $dup) {
            $keepId = $dup->keep_id;

            // Trouver les IDs a supprimer (tous sauf le premier)
            $toDelete = DB::table('country_visa_types')
                ->where('country_id', $dup->country_id)
                ->where('visa_type_id', $dup->visa_type_id)
                ->where('id', '!=', $keepId)
                ->pluck('id');

            foreach ($toDelete as $deleteId) {
                // Transferer les documents du pivot vers le CVT a conserver
                $pivotRecords = DB::table('country_visa_type_required_document')
                    ->where('country_visa_type_id', $deleteId)
                    ->get();

                foreach ($pivotRecords as $pivot) {
                    // Verifier si cette combinaison existe deja
                    $exists = DB::table('country_visa_type_required_document')
                        ->where('country_visa_type_id', $keepId)
                        ->where('required_document_id', $pivot->required_document_id)
                        ->where(function ($query) use ($pivot) {
                            if ($pivot->status_mat === null) {
                                $query->whereNull('status_mat');
                            } else {
                                $query->where('status_mat', $pivot->status_mat);
                            }
                        })
                        ->where(function ($query) use ($pivot) {
                            if ($pivot->min_age === null) {
                                $query->whereNull('min_age');
                            } else {
                                $query->where('min_age', $pivot->min_age);
                            }
                        })
                        ->where(function ($query) use ($pivot) {
                            if ($pivot->max_age === null) {
                                $query->whereNull('max_age');
                            } else {
                                $query->where('max_age', $pivot->max_age);
                            }
                        })
                        ->exists();

                    if (!$exists) {
                        // Inserer avec le nouveau CVT ID
                        DB::table('country_visa_type_required_document')->insert([
                            'id' => (string) \Illuminate\Support\Str::uuid(),
                            'country_visa_type_id' => $keepId,
                            'required_document_id' => $pivot->required_document_id,
                            'status_mat' => $pivot->status_mat,
                            'min_age' => $pivot->min_age,
                            'max_age' => $pivot->max_age,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }

                // Supprimer les anciens enregistrements du pivot
                DB::table('country_visa_type_required_document')
                    ->where('country_visa_type_id', $deleteId)
                    ->delete();

                // Supprimer le CVT duplique
                DB::table('country_visa_types')
                    ->where('id', $deleteId)
                    ->delete();
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Non reversible - les donnees ont ete fusionnees
    }
};
