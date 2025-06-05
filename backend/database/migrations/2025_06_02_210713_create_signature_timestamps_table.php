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
        Schema::create('signature_timestamps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('signature_id')->constrained('document_signatures')->onDelete('cascade');
            $table->text('timestamp_token');
            $table->string('hash_algorithm', 20)->default('SHA256');
            $table->timestamp('timestamp_time');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('signature_timestamps');
    }
};
