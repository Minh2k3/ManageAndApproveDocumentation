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
        Schema::create('document_signatures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_flow_step_id')->constrained()->onDelete('cascade');
            $table->foreignId('document_version_id')->constrained()->onDelete('cascade');
            $table->foreignId('certificate_id')->constrained('certificates');
            $table->text('signature');
            $table->timestamp('signed_at');
            $table->boolean('is_valid')->default(true);
            $table->text('verification_details')->nullable();
            $table->timestamps();

            $table->index(['document_flow_step_id', 'document_version_id'], 'idx_flow_step_version');
            $table->index('signed_at', 'idx_signed_at');
            $table->index('certificate_id', 'idx_certificate_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_signatures');
    }
};
