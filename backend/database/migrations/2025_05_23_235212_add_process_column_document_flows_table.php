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
        Schema::table('document_flows', function (Blueprint $table) {
            $table->integer('process')->default(0)->comment('The process of the document flow')->after('is_template');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('document_flows', function (Blueprint $table) {
            $table->dropColumn('process');
        });
    }
};
