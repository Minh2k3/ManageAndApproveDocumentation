<?php
// database/migrations/2025_08_16_165200_create_wishes_table.php

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
        Schema::create('wishes', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique()->comment('Mã lời chúc duy nhất (VD: WS2025001)');
            $table->string('sender_name', 100)->comment('Tên người gửi');
            $table->text('content')->comment('Nội dung lời chúc chính');
            $table->decimal('position_x', 5, 2)->default(50.00)->comment('Vị trí X trên canvas (%)');
            $table->decimal('position_y', 5, 2)->default(50.00)->comment('Vị trí Y trên canvas (%)');
            $table->decimal('rotation', 5, 2)->default(0.00)->comment('Góc xoay note (-45 đến 45 độ)');
            $table->boolean('is_active')->default(true)->comment('Trạng thái hiển thị');
            $table->timestamps();

            // Indexes for better performance
            $table->index('code', 'idx_wishes_code');
            $table->index('created_at', 'idx_wishes_created_at');
            $table->index(['is_active', 'created_at'], 'idx_wishes_active_created');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wishes');
    }
};