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
        Schema::create('document_flow_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_flow_id')->constrained();
            $table->unsignedInteger('step');
            $table->foreignId('department_id')->constrained();
            $table->timestamp('signed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_flow_steps');
    }
};
