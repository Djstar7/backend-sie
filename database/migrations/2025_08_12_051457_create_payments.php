<?php

use App\Models\User;
use App\Models\VisaRequest;
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
        Schema::create('payments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('visa_request_id')
                ->constrained('visa_requests')
                ->cascadeOnDelete();
            $table->decimal('amount', 10, 2);
            $table->string('transaction_id')->unique();
            $table->enum('method', ['mtn', 'orange', 'visa', 'paypal'])
                ->default('mtn');
            $table->enum('currency', [
                'XAF',
                'USD',
                'EUR',
                'GBP',
                'CAD',
                'NGN',
                'AUD',
                'JPY',
                'CHF',
                'CNY',
                'GHS',
                'ZAR',
                'KES',
                'UGX',
                'TZS',
                'EGP',
                'SAR',
                'AED'
            ])->default('XAF');
            $table->enum('status', ['pending', 'delete', 'failed', 'processing', 'completed', 'canceled', 'expired'])
                ->default('pending');
            $table->json('meta');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
