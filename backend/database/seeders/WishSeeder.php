<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Wish;
use App\Models\WishMedia;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class WishSeeder extends Seeder
{
    public function run(): void
    {
        \Log::info('🌱 Starting WishSeeder...');
        
        // Clear existing data safely
        $this->clearExistingData();
        
        $sampleWishes = [
            [
                'sender_name' => 'Nguyễn Văn An',
                'content' => 'Chúc Minh2k3 một năm mới thật nhiều sức khỏe, hạnh phúc và thành công! Mong rằng tất cả những ước mơ của bạn sẽ trở thành hiện thực. Chúc bạn luôn vui vẻ và gặp nhiều may mắn!',
                'position_x' => 15.50,
                'position_y' => 12.80,
                'rotation' => -8.50,
                'created_at' => Carbon::parse('2025-08-15 10:30:00'),
                'has_images' => true,
                'has_audio' => false
            ],
            [
                'sender_name' => 'Trần Thị Bình', 
                'content' => 'Gửi đến Minh2k3 những lời chúc tốt đẹp nhất từ trái tim! Chúc bạn luôn vui vẻ, may mắn và gặp nhiều điều tốt lành trong cuộc sống. Hy vọng friendship của chúng ta sẽ mãi bền vững!',
                'position_x' => 65.20,
                'position_y' => 25.60,
                'rotation' => 12.30,
                'created_at' => Carbon::parse('2025-08-16 14:15:00'),
                'has_images' => true,
                'has_audio' => true
            ],
            [
                'sender_name' => 'Lê Minh Cường',
                'content' => 'Chúc mừng và chúc Minh2k3 thật nhiều niềm vui trong những ngày tới! Bạn là người bạn tuyệt vời và tôi rất trân trọng tình bạn này.',
                'position_x' => 28.90,
                'position_y' => 48.70,
                'rotation' => 5.20,
                'created_at' => Carbon::parse('2025-08-16 16:45:00'),
                'has_images' => false,
                'has_audio' => false
            ],
            [
                'sender_name' => 'Phạm Thu Hà',
                'content' => 'Chúc Minh2k3 có một ngày thật tuyệt vời và nhiều điều may mắn! Cảm ơn bạn đã luôn là người bạn tốt, luôn sẵn sàng giúp đỡ mọi người.',
                'position_x' => 72.40,
                'position_y' => 38.20,
                'rotation' => -6.70,
                'created_at' => Carbon::parse('2025-08-16 18:20:00'),
                'has_images' => true,
                'has_audio' => false
            ],
            [
                'sender_name' => 'Hoàng Minh Đức',
                'content' => 'Gửi Minh2k3 những lời chúc ấm áp nhất từ trái tim. Chúc bạn luôn khỏe mạnh, hạnh phúc và thành công trong mọi việc làm! Bạn xứng đáng có những điều tốt đẹp nhất.',
                'position_x' => 12.10,
                'position_y' => 62.50,
                'rotation' => 9.80,
                'created_at' => Carbon::parse('2025-08-17 07:30:00'),
                'has_images' => false,
                'has_audio' => true
            ],
            [
                'sender_name' => 'Võ Thị Mai',
                'content' => 'Happy weekend Minh2k3! Chúc bạn có những phút giây thư giãn tuyệt vời. Cảm ơn bạn đã làm cho cuộc sống của mọi người xung quanh trở nên tươi sáng hơn! 🌟',
                'position_x' => 55.60,
                'position_y' => 15.40,
                'rotation' => -12.10,
                'created_at' => Carbon::parse('2025-08-17 08:00:00'),
                'has_images' => true,
                'has_audio' => false
            ],
            [
                'sender_name' => 'Đặng Quốc Việt',
                'content' => 'Minh2k3 ơi, chúc bạn một ngày mới tràn đầy năng lượng và động lực! Hy vọng bạn sẽ đạt được mọi mục tiêu đã đề ra.',
                'position_x' => 38.30,
                'position_y' => 70.20,
                'rotation' => 15.60,
                'created_at' => Carbon::parse('2025-08-17 08:15:00'),
                'has_images' => false,
                'has_audio' => false
            ],
            [
                'sender_name' => 'Bùi Thảo Nguyên',
                'content' => 'Chào Minh2k3! Cảm ơn bạn đã tạo ra space tuyệt vời này để mọi người có thể chia sẻ. Chúc bạn luôn duy trì được tinh thần tích cực và sáng tạo như vậy! 💫',
                'position_x' => 78.80,
                'position_y' => 55.90,
                'rotation' => -3.40,
                'created_at' => Carbon::parse('2025-08-17 08:25:00'),
                'has_images' => true,
                'has_audio' => true
            ],
            [
                'sender_name' => 'Lý Hồng Phúc',
                'content' => 'Chúc Minh2k3 cuối tuần vui vẻ! Mong bạn có thời gian nghỉ ngơi thật tốt sau những ngày làm việc vất vả.',
                'position_x' => 20.70,
                'position_y' => 35.80,
                'rotation' => 7.20,
                'created_at' => Carbon::parse('2025-08-17 08:35:00'),
                'has_images' => false,
                'has_audio' => false
            ],
            [
                'sender_name' => 'Phan Minh Tú',
                'content' => 'Minh2k3, bạn thật là amazing! Chúc bạn luôn giữ được nụ cười tươi và tinh thần lạc quan. Những người như bạn làm cho thế giới này trở nên đẹp hơn! ✨',
                'position_x' => 45.20,
                'position_y' => 22.40,
                'rotation' => -9.90,
                'created_at' => Carbon::parse('2025-08-17 08:40:00'),
                'has_images' => true,
                'has_audio' => false
            ]
        ];

        foreach ($sampleWishes as $index => $wishData) {
            $wish = Wish::create([
                'sender_name' => $wishData['sender_name'],
                'content' => $wishData['content'],
                'position_x' => $wishData['position_x'],
                'position_y' => $wishData['position_y'],
                'rotation' => $wishData['rotation'],
                'is_active' => true,
                'created_at' => $wishData['created_at'],
                'updated_at' => $wishData['created_at']
            ]);

            \Log::info("✅ Created wish: {$wish->code} by {$wish->sender_name}");

            // Add sample images
            if ($wishData['has_images']) {
                $this->createSampleImages($wish, rand(1, 3));
            }

            // Add sample audio
            if ($wishData['has_audio']) {
                $this->createSampleAudio($wish);
            }
        }

        \Log::info('🎉 WishSeeder completed! Created ' . Wish::count() . ' wishes');
        $this->command->info('🎉 Created ' . Wish::count() . ' sample wishes with media');
    }

    /**
     * Clear existing data safely by respecting foreign key constraints
     */
    private function clearExistingData()
    {
        try {
            \Log::info('🗑️ Clearing existing wishes data...');
            
            // Disable foreign key checks temporarily
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            
            // Clear media files from storage
            $this->clearMediaFiles();
            
            // Truncate tables in correct order (child first, then parent)
            WishMedia::truncate();
            Wish::truncate();
            
            // Re-enable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            
            \Log::info('✅ Successfully cleared existing data');
            
        } catch (\Exception $e) {
            \Log::error('❌ Error clearing data: ' . $e->getMessage());
            
            // Re-enable foreign key checks even if error occurs
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            
            // Alternative: Delete manually if truncate fails
            $this->deleteDataManually();
        }
    }

    /**
     * Alternative deletion method if truncate fails
     */
    private function deleteDataManually()
    {
        try {
            \Log::info('🔄 Using alternative deletion method...');
            
            // Delete media first (child records)
            $mediaCount = WishMedia::count();
            WishMedia::query()->delete();
            \Log::info("Deleted {$mediaCount} media records");
            
            // Then delete wishes (parent records)
            $wishCount = Wish::count();
            Wish::query()->delete();
            \Log::info("Deleted {$wishCount} wish records");
            
            // Reset auto increment
            DB::statement('ALTER TABLE wish_media AUTO_INCREMENT = 1');
            DB::statement('ALTER TABLE wishes AUTO_INCREMENT = 1');
            
        } catch (\Exception $e) {
            \Log::error('❌ Error in manual deletion: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Clear media files from storage
     */
    private function clearMediaFiles()
    {
        try {
            $disk = Storage::disk('wishes');
            
            // Clear images directory
            if ($disk->exists('images')) {
                $imageFiles = $disk->allFiles('images');
                foreach ($imageFiles as $file) {
                    $disk->delete($file);
                }
                \Log::info('Cleared ' . count($imageFiles) . ' image files');
            }
            
            // Clear audios directory
            if ($disk->exists('audios')) {
                $audioFiles = $disk->allFiles('audios');
                foreach ($audioFiles as $file) {
                    $disk->delete($file);
                }
                \Log::info('Cleared ' . count($audioFiles) . ' audio files');
            }
            
        } catch (\Exception $e) {
            \Log::warning('⚠️ Could not clear media files: ' . $e->getMessage());
        }
    }

    private function createSampleImages($wish, $count = 1)
    {
        for ($i = 1; $i <= $count; $i++) {
            try {
                // Create sample image content (simple base64 PNG)
                $imageContent = $this->createSamplePngContent();
                
                // Create directory structure
                $year = $wish->created_at->year;
                $month = str_pad($wish->created_at->month, 2, '0', STR_PAD_LEFT);
                $directory = "images/{$year}/{$month}";
                
                // Create filename
                $fileName = "sample_{$wish->id}_{$i}_" . time() . '_' . rand(1000, 9999) . '.png';
                $filePath = "{$directory}/{$fileName}";
                
                // Store file
                Storage::disk('wishes')->put($filePath, $imageContent);
                
                // Save to database
                WishMedia::create([
                    'wish_id' => $wish->id,
                    'type' => 'image',
                    'file_name' => $fileName,
                    'file_path' => $filePath,
                    'file_size' => strlen($imageContent),
                    'mime_type' => 'image/png',
                    'original_name' => "sample_image_{$i}.png"
                ]);
                
                \Log::info("📷 Created sample image for wish {$wish->code}: {$fileName}");
                
            } catch (\Exception $e) {
                \Log::error("❌ Failed to create image for wish {$wish->code}: " . $e->getMessage());
            }
        }
    }

    private function createSampleAudio($wish)
    {
        try {
            // Create sample audio content (minimal WAV file)
            $audioContent = $this->createSampleWavContent();
            
            // Create directory structure  
            $year = $wish->created_at->year;
            $month = str_pad($wish->created_at->month, 2, '0', STR_PAD_LEFT);
            $directory = "audios/{$year}/{$month}";
            
            // Create filename
            $fileName = "sample_audio_{$wish->id}_" . time() . '_' . rand(1000, 9999) . '.wav';
            $filePath = "{$directory}/{$fileName}";
            
            // Store file
            Storage::disk('wishes')->put($filePath, $audioContent);
            
            // Save to database
            WishMedia::create([
                'wish_id' => $wish->id,
                'type' => 'audio',
                'file_name' => $fileName,
                'file_path' => $filePath,
                'file_size' => strlen($audioContent),
                'mime_type' => 'audio/wav',
                'original_name' => 'sample_audio.wav'
            ]);
            
            \Log::info("🎵 Created sample audio for wish {$wish->code}: {$fileName}");
            
        } catch (\Exception $e) {
            \Log::error("❌ Failed to create audio for wish {$wish->code}: " . $e->getMessage());
        }
    }

    /**
     * Create a simple PNG file content
     */
    private function createSamplePngContent()
    {
        // Simple 1x1 transparent PNG (smallest valid PNG)
        $pngData = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChAI/hRxuoAAAAABJRU5ErkJggg==');
        return $pngData;
    }

    /**
     * Create a minimal WAV file content
     */
    private function createSampleWavContent()
    {
        // Minimal WAV file (44 bytes header + minimal data)
        $header = '';
        $header .= 'RIFF';                          // ChunkID
        $header .= pack('V', 36);                   // ChunkSize
        $header .= 'WAVE';                          // Format
        $header .= 'fmt ';                          // Subchunk1ID
        $header .= pack('V', 16);                   // Subchunk1Size
        $header .= pack('v', 1);                    // AudioFormat
        $header .= pack('v', 1);                    // NumChannels
        $header .= pack('V', 8000);                 // SampleRate
        $header .= pack('V', 8000);                 // ByteRate
        $header .= pack('v', 1);                    // BlockAlign
        $header .= pack('v', 8);                    // BitsPerSample
        $header .= 'data';                          // Subchunk2ID
        $header .= pack('V', 0);                    // Subchunk2Size
        
        return $header;
    }
}