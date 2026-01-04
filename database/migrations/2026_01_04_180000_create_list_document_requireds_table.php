<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('list_document_requireds', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->text('guide')->nullable();
            $table->string('category'); // administratifs, financiers, voyage, etc.
            $table->boolean('is_required')->default(true);
            $table->json('file_types'); // ['pdf', 'jpg', 'png']
            $table->integer('max_size_mb')->default(5);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Charger les donnees initiales
        $this->seedInitialData();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('list_document_requireds');
    }

    /**
     * Seed initial data from the existing data.ts structure
     */
    private function seedInitialData(): void
    {
        $documents = [
            // Administratifs
            [
                'name' => 'Formulaire de demande de visa',
                'guide' => 'Telecharger le formulaire officiel sur le site de l\'ambassade.',
                'category' => 'administratifs',
                'is_required' => true,
                'file_types' => ['pdf'],
                'max_size_mb' => 2,
            ],
            [
                'name' => 'Passeport valide',
                'guide' => 'Passeport valable au moins 6 mois apres la date de retour.',
                'category' => 'administratifs',
                'is_required' => true,
                'file_types' => ['pdf', 'jpg', 'png'],
                'max_size_mb' => 5,
            ],
            [
                'name' => 'Photocopies du passeport',
                'guide' => 'Toutes les pages importantes (photo, visas, tampons).',
                'category' => 'administratifs',
                'is_required' => true,
                'file_types' => ['pdf'],
                'max_size_mb' => 3,
            ],
            [
                'name' => 'Carte nationale d\'identite',
                'guide' => 'Copie recto-verso de la CNI en cours de validite.',
                'category' => 'administratifs',
                'is_required' => true,
                'file_types' => ['pdf', 'jpg', 'png'],
                'max_size_mb' => 3,
            ],
            [
                'name' => 'Photographies d\'identite',
                'guide' => 'Photos recentes aux normes biometriques (format passeport).',
                'category' => 'administratifs',
                'is_required' => true,
                'file_types' => ['jpg', 'png'],
                'max_size_mb' => 2,
            ],
            [
                'name' => 'Acte de naissance',
                'guide' => 'Copie integrale de l\'acte de naissance.',
                'category' => 'administratifs',
                'is_required' => true,
                'file_types' => ['pdf'],
                'max_size_mb' => 2,
            ],
            [
                'name' => 'Acte de mariage',
                'guide' => 'Copie integrale en cas de mariage.',
                'category' => 'administratifs',
                'is_required' => true,
                'file_types' => ['pdf'],
                'max_size_mb' => 2,
            ],
            [
                'name' => 'Acte de divorce',
                'guide' => 'Jugement de divorce le cas echeant.',
                'category' => 'administratifs',
                'is_required' => true,
                'file_types' => ['pdf'],
                'max_size_mb' => 2,
            ],
            [
                'name' => 'Livret de famille',
                'guide' => 'Pages concernant le demandeur et les enfants.',
                'category' => 'administratifs',
                'is_required' => true,
                'file_types' => ['pdf'],
                'max_size_mb' => 3,
            ],
            [
                'name' => 'Autorisation parentale pour mineur',
                'guide' => 'Lettre signee par les parents + copie de leurs pieces d\'identite.',
                'category' => 'administratifs',
                'is_required' => true,
                'file_types' => ['pdf'],
                'max_size_mb' => 2,
            ],

            // Financiers
            [
                'name' => 'Preuve de paiement des frais de visa',
                'guide' => 'Recu ou justificatif du paiement effectue.',
                'category' => 'financiers',
                'is_required' => true,
                'file_types' => ['pdf', 'jpg'],
                'max_size_mb' => 2,
            ],
            [
                'name' => 'Releves bancaires (3 a 6 derniers mois)',
                'guide' => 'Scanner vos releves bancaires en PDF lisible.',
                'category' => 'financiers',
                'is_required' => true,
                'file_types' => ['pdf'],
                'max_size_mb' => 10,
            ],
            [
                'name' => 'Attestation de prise en charge / Lettre de sponsor',
                'guide' => 'Lettre signee + piece d\'identite du garant.',
                'category' => 'financiers',
                'is_required' => true,
                'file_types' => ['pdf'],
                'max_size_mb' => 5,
            ],
            [
                'name' => 'Bulletins de salaire',
                'guide' => '3 derniers bulletins de salaire du demandeur.',
                'category' => 'financiers',
                'is_required' => true,
                'file_types' => ['pdf'],
                'max_size_mb' => 5,
            ],
            [
                'name' => 'Attestation de travail',
                'guide' => 'Lettre de l\'employeur confirmant le poste et l\'anciennete.',
                'category' => 'financiers',
                'is_required' => true,
                'file_types' => ['pdf'],
                'max_size_mb' => 3,
            ],
            [
                'name' => 'Contrat de travail',
                'guide' => 'Copie signee de votre contrat de travail.',
                'category' => 'financiers',
                'is_required' => true,
                'file_types' => ['pdf'],
                'max_size_mb' => 5,
            ],
            [
                'name' => 'Declaration d\'impots / Avis d\'imposition',
                'guide' => 'Derniere declaration ou avis officiel.',
                'category' => 'financiers',
                'is_required' => true,
                'file_types' => ['pdf'],
                'max_size_mb' => 5,
            ],
            [
                'name' => 'Justificatifs de biens',
                'guide' => 'Titres de propriete, certificats de vehicule, etc.',
                'category' => 'financiers',
                'is_required' => true,
                'file_types' => ['pdf', 'jpg'],
                'max_size_mb' => 5,
            ],

            // Voyage
            [
                'name' => 'Reservation d\'hotel ou attestation d\'hebergement',
                'guide' => 'Reservation confirmee ou lettre de l\'hebergeant.',
                'category' => 'voyage',
                'is_required' => true,
                'file_types' => ['pdf', 'jpg'],
                'max_size_mb' => 3,
            ],
            [
                'name' => 'Lettre d\'invitation',
                'guide' => 'Lettre officielle de la personne ou institution qui vous invite.',
                'category' => 'voyage',
                'is_required' => true,
                'file_types' => ['pdf'],
                'max_size_mb' => 3,
            ],
            [
                'name' => 'Billet d\'avion (aller-retour ou reservation)',
                'guide' => 'Billet confirme ou reservation avec dates precises.',
                'category' => 'voyage',
                'is_required' => true,
                'file_types' => ['pdf'],
                'max_size_mb' => 5,
            ],
            [
                'name' => 'Assurance voyage',
                'guide' => 'Attestation d\'assurance couvrant toute la duree du sejour.',
                'category' => 'voyage',
                'is_required' => true,
                'file_types' => ['pdf'],
                'max_size_mb' => 2,
            ],
            [
                'name' => 'Itineraire de voyage detaille',
                'guide' => 'Plan de sejour avec lieux et dates.',
                'category' => 'voyage',
                'is_required' => true,
                'file_types' => ['pdf'],
                'max_size_mb' => 2,
            ],

            // Academiques / Professionnels
            [
                'name' => 'Lettre de motivation',
                'guide' => 'Lettre expliquant les raisons de la demande et le projet.',
                'category' => 'academiques_professionnels',
                'is_required' => true,
                'file_types' => ['pdf'],
                'max_size_mb' => 2,
            ],
            [
                'name' => 'Lettre d\'admission (etudes)',
                'guide' => 'Lettre officielle de l\'universite ou ecole.',
                'category' => 'academiques_professionnels',
                'is_required' => true,
                'file_types' => ['pdf'],
                'max_size_mb' => 3,
            ],
            [
                'name' => 'Preuve de paiement des frais de scolarite',
                'guide' => 'Recu de paiement de l\'etablissement scolaire.',
                'category' => 'academiques_professionnels',
                'is_required' => true,
                'file_types' => ['pdf'],
                'max_size_mb' => 3,
            ],
            [
                'name' => 'Diplomes / certificats / releves de notes',
                'guide' => 'Copies certifiees conformes des diplomes obtenus.',
                'category' => 'academiques_professionnels',
                'is_required' => true,
                'file_types' => ['pdf'],
                'max_size_mb' => 10,
            ],
            [
                'name' => 'Lettre d\'invitation d\'une entreprise',
                'guide' => 'Lettre signee precisant l\'objet du voyage professionnel.',
                'category' => 'academiques_professionnels',
                'is_required' => true,
                'file_types' => ['pdf'],
                'max_size_mb' => 3,
            ],
            [
                'name' => 'Preuve d\'inscription a un congres',
                'guide' => 'Justificatif officiel de l\'inscription.',
                'category' => 'academiques_professionnels',
                'is_required' => true,
                'file_types' => ['pdf'],
                'max_size_mb' => 3,
            ],

            // Medicaux / Judiciaires
            [
                'name' => 'Certificat medical',
                'guide' => 'Certificat signe par un medecin agree.',
                'category' => 'medicaux_judiciaires',
                'is_required' => true,
                'file_types' => ['pdf', 'jpg'],
                'max_size_mb' => 2,
            ],
            [
                'name' => 'Carnet de vaccination',
                'guide' => 'Pages attestant des vaccinations obligatoires.',
                'category' => 'medicaux_judiciaires',
                'is_required' => true,
                'file_types' => ['pdf', 'jpg', 'png'],
                'max_size_mb' => 3,
            ],
            [
                'name' => 'Casier judiciaire recent',
                'guide' => 'Extrait du casier judiciaire de moins de 3 mois.',
                'category' => 'medicaux_judiciaires',
                'is_required' => true,
                'file_types' => ['pdf'],
                'max_size_mb' => 2,
            ],
            [
                'name' => 'Test de depistage (VIH, tuberculose, COVID)',
                'guide' => 'Resultats des tests recents exiges.',
                'category' => 'medicaux_judiciaires',
                'is_required' => true,
                'file_types' => ['pdf'],
                'max_size_mb' => 2,
            ],

            // Autres
            [
                'name' => 'Lettre d\'engagement de retour au pays',
                'guide' => 'Declaration signee du demandeur.',
                'category' => 'autres',
                'is_required' => true,
                'file_types' => ['pdf'],
                'max_size_mb' => 2,
            ],
            [
                'name' => 'Attestation d\'employeur de conge',
                'guide' => 'Lettre signee confirmant la duree du conge.',
                'category' => 'autres',
                'is_required' => true,
                'file_types' => ['pdf'],
                'max_size_mb' => 2,
            ],
            [
                'name' => 'Certificat de scolarite (mineur)',
                'guide' => 'Certificat recent de l\'etablissement frequente.',
                'category' => 'autres',
                'is_required' => true,
                'file_types' => ['pdf'],
                'max_size_mb' => 2,
            ],
            [
                'name' => 'Preuve de ressources du garant',
                'guide' => 'Attestation bancaire, fiches de paie ou autres justificatifs.',
                'category' => 'autres',
                'is_required' => true,
                'file_types' => ['pdf'],
                'max_size_mb' => 5,
            ],
            [
                'name' => 'Autorisation de sortie du territoire',
                'guide' => 'Document officiel pour les mineurs voyageant seuls.',
                'category' => 'autres',
                'is_required' => true,
                'file_types' => ['pdf'],
                'max_size_mb' => 2,
            ],
            [
                'name' => 'Lettre explicative',
                'guide' => 'Lettre justifiant une situation particuliere.',
                'category' => 'autres',
                'is_required' => true,
                'file_types' => ['pdf'],
                'max_size_mb' => 2,
            ],
        ];

        $now = now();

        foreach ($documents as $doc) {
            DB::table('list_document_requireds')->insert([
                'id' => Str::uuid(),
                'name' => $doc['name'],
                'guide' => $doc['guide'],
                'category' => $doc['category'],
                'is_required' => $doc['is_required'],
                'file_types' => json_encode($doc['file_types']),
                'max_size_mb' => $doc['max_size_mb'],
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
};
