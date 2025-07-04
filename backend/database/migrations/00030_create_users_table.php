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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->boolean('email_verified')->default(false);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('phone')->nullable();
            $table->text('description')->nullable();
            $table->string('password');
            $table->foreignId('role_id')->constrained();
            $table->foreignId('signature_id')->nullable()->constrained('digital_signatures')->nullOnDelete();
            $table->string('avatar')->nullable();
            $table->enum('sex', ['male', 'female'])->nullable();
            $table->enum('status', ['inactive', 'pending', 'active', 'banned'])->default('inactive')
                ->comment('Trạng thái tài khoản, inactive: chưa kích hoạt, pending: đang chờ duyệt, active: đã kích hoạt, banned: bị cấm');
            $table->string('verification_token')->nullable();
            $table->timestamp('verification_token_expiry')->nullable();
            $table->integer('verification_resent_count')->default(0);
            $table->timestamp('last_verification_resent_at')->nullable();
            $table->rememberToken(100);
            $table->timestamps();
            $table->comment('Lưu thông tin người dùng');
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
