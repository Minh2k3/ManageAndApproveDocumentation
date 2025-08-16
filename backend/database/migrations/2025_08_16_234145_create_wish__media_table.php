<?php
// database/migrations/2025_08_16_165201_create_wish_media_table.php

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
        Schema::create('wish_media', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('wish_id')->comment('ID lời chúc');
            $table->enum('type', ['image', 'audio'])->comment('Loại media: image hoặc audio');
            $table->string('file_name')->comment('Tên file được lưu');
            $table->string('file_path', 500)->comment('Đường dẫn file trong storage');
            $table->unsignedInteger('file_size')->nullable()->comment('Kích thước file (bytes)');
            $table->string('mime_type', 100)->nullable()->comment('MIME type của file');
            $table->string('original_name')->nullable()->comment('Tên file gốc khi upload');
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('wish_id')->references('id')->on('wishes')->onDelete('cascade');
            
            // Indexes for better performance
            $table->index('wish_id', 'idx_wish_media_wish_id');
            $table->index('type', 'idx_wish_media_type');
            $table->index(['wish_id', 'type'], 'idx_wish_media_wish_type');
            $table->index('created_at', 'idx_wish_media_created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wish_media');
    }
};