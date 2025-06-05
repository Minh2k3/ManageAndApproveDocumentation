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
        Schema::create('digital_certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('certificate_pem');
            $table->text('private_key_encrypted');
            $table->string('serial_number', 32)->unique();
            $table->string('subject');
            $table->string('issuer')->default('CN=TLU Mock CA,O=Dai hoc Thuy loi,C=VN');
            $table->timestamp('valid_from');
            $table->timestamp('valid_to');
            $table->boolean('is_revoked')->default(false);
            $table->string('thumbprint', 64)->nullable();
            $table->foreignId('parent_certificate_id')->nullable()->constrained('digital_certificates');
            $table->enum('renewal_status', ['active', 'expiring_soon', 'expired', 'renewed'])->default('active');
            $table->timestamp('renewal_requested_at')->nullable();
            $table->timestamp('expiry_notification_sent_at')->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'is_revoked', 'renewal_status']);
            $table->index('valid_to');
            $table->index('serial_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('digital_certificates');
    }
};
