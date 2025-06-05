<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\CertificateRenewalService;

class CheckExpiringCertificates extends Command
{
    protected $signature = 'certificates:check-expiring';
    protected $description = 'Check for expiring certificates and send notifications';
    
    protected $renewalService;
    
    public function __construct(CertificateRenewalService $renewalService)
    {
        parent::__construct();
        $this->renewalService = $renewalService;
    }
    
    public function handle()
    {
        $this->info('Checking expiring certificates...');
        
        try {
            $results = $this->renewalService->checkExpiringCertificates();
            
            $this->info("✅ Notifications sent: {$results['notifications_sent']}");
            $this->info("⚠️ Marked as expiring: {$results['marked_expiring']}");
            $this->info("❌ Marked as expired: {$results['marked_expired']}");
            
            $this->info('Certificate check completed successfully.');
            
        } catch (\Exception $e) {
            $this->error('Error checking certificates: ' . $e->getMessage());
            return 1;
        }
        
        return 0;
    }
}