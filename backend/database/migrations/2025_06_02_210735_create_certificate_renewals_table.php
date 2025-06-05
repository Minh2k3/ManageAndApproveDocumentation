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
        Schema::create('certificate_renewals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('old_certificate_id')->constrained('digital_certificates');
            $table->foreignId('new_certificate_id')->nullable()->constrained('digital_certificates');
            $table->enum('renewal_type', ['renewal', 'reissue']);
            $table->text('reason')->nullable();
            $table->enum('status', ['pending', 'approved', 'completed', 'rejected']);
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->timestamps();
            
            $table->index('status');
            $table->index('old_certificate_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificate_renewals');
    }
};
