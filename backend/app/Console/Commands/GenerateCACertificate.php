<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\CertificateService;
use App\Models\CaCertificate;
use Carbon\Carbon;

class GenerateCACertificate extends Command
{
    protected $signature = 'certificate:generate-ca';
    protected $description = 'Generate CA certificate and private key';

    public function handle()
    {
        $service = new CertificateService();
        $result = $service->generateCACertificate();

        // Lưu vào database
        CaCertificate::create([
            'name' => config('certificate.ca_name', 'TLU Document Management CA'),
            'private_key' => encrypt($result['private_key']),
            'certificate' => $result['certificate'],
            'issued_at' => Carbon::now(),
            'expires_at' => Carbon::now()->addYears(10),
        ]);

        $this->info('CA certificate generated and saved successfully!');
    }
}