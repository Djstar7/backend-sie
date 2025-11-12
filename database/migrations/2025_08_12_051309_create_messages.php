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
        Schema::create('messages', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->foreignUuid('visa_request_id')
                ->constrained('visa_requests')
                ->cascadeOnDelete();
            // $table->string('subject', 255);
            $table->text('content');
            $table->enum('status', ['sending', 'sent', 'read', 'failed'])
                ->default('sending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
