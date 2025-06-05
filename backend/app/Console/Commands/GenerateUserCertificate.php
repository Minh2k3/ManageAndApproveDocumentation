<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\CertificateService;
use App\Models\User;

class GenerateUserCertificate extends Command
{
    protected $signature = 'certificate:generate {user_id} {--department=}';
    protected $description = 'Generate certificate for a specific user';
    
    protected $certificateService;
    
    public function __construct(CertificateService $certificateService)
    {
        parent::__construct();
        $this->certificateService = $certificateService;
    }
    
    public function handle()
    {
        $userId = $this->argument('user_id');
        $departmentName = $this->option('department');
        
        try {
            $user = User::findOrFail($userId);
            
            // Check if user already has valid certificate
            $existingCert = $this->certificateService->getValidCertificate($userId);
            if ($existingCert) {
                $this->error("User already has a valid certificate (Serial: {$existingCert->serial_number})");
                return 1;
            }
            
            $departmentInfo = $departmentName ? ['name' => $departmentName] : null;
            $certificate = $this->certificateService->generateUserCertificate($user, $departmentInfo);
            
            $this->info("✅ Certificate generated successfully!");
            $this->info("User: {$user->name} ({$user->email})");
            $this->info("Serial Number: {$certificate->serial_number}");
            $this->info("Valid Until: {$certificate->valid_to->format('Y-m-d')}");
            
        } catch (\Exception $e) {
            $this->error('Error generating certificate: ' . $e->getMessage());
            return 1;
        }
        
        return 0;
    }
}