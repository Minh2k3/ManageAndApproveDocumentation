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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('notification_category_id')->constrained();
            $table->foreignId('from_user_id')->nullable()->constrained('users')->after('notification_category_id');
            $table->foreignId('receiver_id')->constrained('users');
            $table->string('title');
            $table->text('content');
            $table->json('data')->nullable()->comment('Additional data related to the notification');
            $table->boolean('is_read')->default(false);
            $table->timestamp('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
