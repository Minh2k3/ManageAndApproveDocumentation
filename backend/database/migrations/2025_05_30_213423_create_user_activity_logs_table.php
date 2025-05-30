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
        Schema::create('user_activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');
            $table->string('activity_type'); // e.g., 'login', 'logout', 'update_profile', etc.
            $table->text('activity_description')->nullable(); // Optional description of the activity
            $table->string('ip_address')->nullable(); // IP address of the user during the activity
            $table->string('user_agent')->nullable(); // User agent string of the browser or device
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_activity_logs');
    }
};
