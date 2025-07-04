<?php

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
        Schema::create('ca_certificates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('private_key');
            $table->text('certificate');
            // $table->enum('status', ['active', 'revoked', 'expired'])->default('active');
            $table->timestamp('issued_at');
            $table->timestamp('expires_at');
            $table->timestamps();

            $table->index('issued_at', 'idx_ca_issued_at');
            $table->index('expires_at', 'idx_ca_expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ca_certificates');
    }
};
