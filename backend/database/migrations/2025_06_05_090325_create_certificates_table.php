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
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete()->comment('ID của người dùng sở hữu chứng chỉ');
            $table->text('public_key');
            $table->text('private_key');
            $table->text('certificate');
            $table->string('signature_image_path')->nullable()->comment('Đường dẫn đến hình ảnh chữ ký số');
            $table->integer('used_count')->default(0)->comment('Số lần chứng chỉ đã được sử dụng');
            $table->timestamp('issued_at');
            $table->timestamp('expires_at');
            $table->enum('status', ['active', 'revoked', 'expired', 'renewal'])->default('active');
            $table->timestamps();

            $table->index(['user_id', 'status'], 'idx_user_status');
            $table->index('issued_at', 'idx_issued_at');
            $table->index('expires_at', 'idx_expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
