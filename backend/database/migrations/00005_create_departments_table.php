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
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('group', ['Khoa/Trung tâm', 'LCĐ', 'LCH', 'Phòng/Ban', 'CLB/Đội', 'Khác'])->default('Khác');
            $table->enum('status', ['active', 'non-active'])->default('active')->comment('check if this department is active or not');
            $table->string('avatar')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('position')->nullable()->comment('where this department situated in');
            $table->boolean('can_approve')->default(false)->comment('check if this department has approval permission or not');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('departments');
    }
};
