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
        Schema::create('approval_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('document_type_id')->constrained('document_types');
            $table->foreignId('granted_by')->constrained('users');
            $table->timestamps();
            $table->timestamp('ended_at')->nullable();
            $table->unique(['user_id', 'document_type_id']);
            $table->comment('Trưởng đơn vị phân quyền ký văn bản cho thành viên trong đơn vị');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approval_permissions');
    }
};
