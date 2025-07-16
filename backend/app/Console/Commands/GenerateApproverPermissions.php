<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GenerateApproverPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-approver-permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate permissions for approvers based on their level';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to generate approver permissions...');
        
        // Lấy danh sách tất cả approvers với level của họ
        $approvers = DB::table('approvers')
            ->join('roll_at_departments', 'approvers.id', '=', 'roll_at_departments.approver_id')
            ->select('approvers.id as approver_id', 'roll_at_departments.level')
            ->get();
        
        // Lấy tất cả document types
        $documentTypes = DB::table('document_types')->select('id')->get();
        
        // Đếm số lượng approvers
        $totalApprovers = count($approvers);
        $this->info("Found {$totalApprovers} approvers to process");
        
        // Xóa dữ liệu cũ trong bảng ApproverHasPermission (tùy chọn)
        DB::table('approver_has_permission')->truncate();
        $this->info('Cleared existing permissions');
        
        $bar = $this->output->createProgressBar($totalApprovers);
        $bar->start();
        
        $permissionsCreated = 0;
        
        foreach ($approvers as $approver) {
            // Với mỗi approver, kiểm tra level và cấp quyền tương ứng
            foreach ($documentTypes as $documentType) {
                $documentTypeId = $documentType->id;
                
                // Nếu level = 1, cấp quyền cho tất cả loại văn bản
                // Nếu level khác 1, chỉ cấp quyền cho document_type_id khác 1 và 2
                if ($approver->level == 1 || ($documentTypeId != 1 && $documentTypeId != 2)) {
                    DB::table('approver_has_permission')->insert([
                        'approver_id' => $approver->approver_id,
                        'document_type_id' => $documentTypeId,
                        'created_at' => now()
                    ]);
                    
                    $permissionsCreated++;
                }
            }
            
            $bar->advance();
        }
        
        $bar->finish();
        $this->newLine();
        $this->info("Created {$permissionsCreated} permissions for {$totalApprovers} approvers");
        $this->info('Approver permissions generated successfully!');
        
        return Command::SUCCESS;
    }
}
