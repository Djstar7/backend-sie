<?php

namespace Database\Seeders;

use App\Models\CountryVisaType;
use App\Models\RequiredDocument;
use Illuminate\Database\Seeder;

class CountryVisaTypeRequiredDocumentSeeder extends Seeder
{
    public function run(): void
    {
        $allDocuments = RequiredDocument::pluck('id');

        $totalDocs = $allDocuments->count();

        CountryVisaType::all()->each(function ($visaType) use ($allDocuments, $totalDocs) {
            $nombreAleatoire = rand(1, $totalDocs);

            $documents = $allDocuments->random($nombreAleatoire);

            $visaType->requiredDocuments()->attach($documents);
        });
    }
}
