<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            DocumentationSeeder::class,
            FaqChatbotSeeder::class,
            CountrySeeder::class,
            RolesTableSeeder::class,
            UserSeeder::class,
            AdminUserSeeder::class,
            ProfilSeeder::class,
            LogSeeder::class,
            VisaTypeSeeder::class,
            RequiredDocumentSeeder::class,
            CountryVisaTypeSeeder::class,
            CountryVisaTypeRequiredDocumentSeeder::class,
            VisaRequestSeeder::class,
            DocumentSeeder::class,
            BackupSeeder::class,
            PaymentSeeder::class,
            ReceiptSeeder::class,
            MessageSeeder::class,
            AppoitmentSeeder::class,
            NotificationSeeder::class,
            MessageSeeder::class,
        ]);
    }
}
