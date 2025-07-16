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
        Schema::create('approver_has_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('approver_id')->constrained()->cascadeOnDelete();
            $table->foreignId('document_type_id')->constrained()->cascadeOnDelete();
            $table->timestamp('created_at');
            $table->timestamp('ended_at')->nullable();
            
            $table->unique(['approver_id', 'document_type_id'], 'approver_type_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approver_has_permissions');
    }
};
