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
            $table->foreignId('roll_at_department_id')->constrained()->cascadeOnDelete();
            $table->foreignId('document_type_id')->constrained()->cascadeOnDelete();
            $table->timestamp('created_at');
            $table->timestamp('ended_at')->nullable();
            
            $table->unique(['roll_at_department_id', 'document_type_id'], 'approval_perm_unique');
            $table->comment('Quyền ký văn bản theo chức vụ trong đơn vị và loại văn bản');
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
