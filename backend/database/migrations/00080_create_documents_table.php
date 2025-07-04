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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('file_path');
            $table->string('certificate_path')->nullable()->comment('Path to the certificate file');
            $table->foreignId('document_type_id')->constrained();
            $table->foreignId('created_by')->constrained('users', 'id')->onDelete('cascade');
            $table->enum('status', ['draft', 'in_review', 'approved', 'rejected'])->default('draft');
            $table->foreignId('document_flow_id')->nullable()->constrained();
            $table->boolean('is_public')->default(false)->comment('Check if this document can display for everyone');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
