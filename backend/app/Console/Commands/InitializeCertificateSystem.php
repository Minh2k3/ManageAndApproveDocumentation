<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\CertificateService;

class InitializeCertificateSystem extends Command
{
    protected $signature = 'certificate:init';
    protected $description = 'Initialize certificate system (create CA certificate)';
    
    public function handle()
    {
        $this->info('Initializing certificate system...');
        
        try {
            // This will trigger CA certificate generation if not exists
            $certificateService = new CertificateService();
            
            $this->info('✅ Certificate system initialized successfully!');
            $this->info('CA certificate created at: ' . storage_path('certificates/ca.pem'));
            
        } catch (\Exception $e) {
            $this->error('Error initializing certificate system: ' . $e->getMessage());
            return 1;
        }
        
        return 0;
    }
}