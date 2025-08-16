<?php
// app/Http/Controllers/WishController.php
namespace App\Http\Controllers;

use App\Models\Wish;
use App\Models\WishMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class WishController extends Controller
{
    public function index(Request $request)
    {
        $wishes = Wish::with(['media', 'user'])
            ->where('is_active', true)
            ->orderBy('created_at', 'desc');

        return response()->json([
            'success' => true,
            'data' => $wishes->map(function ($wish) {
                return [
                    'id' => $wish->id,
                    'code' => $wish->code,
                    'senderName' => $wish->sender_name,
                    'content' => [
                        'text' => $wish->content,
                        'images' => $wish->images->map(fn($img) => Storage::disk('wishes')->url($img->file_path)),
                        'audio' => $wish->audio->first() ? Storage::disk('wishes')->url($wish->audio->first()->file_path) : null
                    ],
                    'position' => $wish->position,
                    'createdAt' => $wish->created_at->toISOString()
                ];
            }),
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sender_name' => 'required|string|max:100',
            'content' => 'required|string|max:500',
            'position_x' => 'numeric|between:0,100',
            'position_y' => 'numeric|between:0,100', 
            'rotation' => 'numeric|between:-45,45',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'audio' => 'mimes:mp3,wav,ogg,m4a|max:10240'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Create wish with current user (Minh2k3)
            $wish = Wish::create([
                'sender_name' => $request->sender_name,
                'content' => $request->content,
                'position_x' => $request->position_x ?? $this->generateRandomPosition()['x'],
                'position_y' => $request->position_y ?? $this->generateRandomPosition()['y'],
                'rotation' => $request->rotation ?? $this->generateRandomPosition()['rotation']
            ]);

            // Handle image uploads
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $this->saveMedia($wish, $image, 'image');
                }
            }

            // Handle audio upload
            if ($request->hasFile('audio')) {
                $this->saveMedia($wish, $request->file('audio'), 'audio');
            }

            // Handle base64 images from frontend
            if ($request->has('base64_images')) {
                foreach ($request->base64_images as $base64Image) {
                    $this->saveBase64Image($wish, $base64Image);
                }
            }

            // Handle audio blob from frontend
            if ($request->has('audio_blob')) {
                $this->saveAudioBlob($wish, $request->audio_blob);
            }

            // Load with media for response
            $wish->load(['media']);

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $wish->id,
                    'code' => $wish->code,
                    'senderName' => $wish->sender_name,
                    'content' => [
                        'text' => $wish->content,
                        'images' => $wish->images->map(fn($img) => Storage::disk('wishes')->url($img->file_path)),
                        'audio' => $wish->audio->first() ? Storage::disk('wishes')->url($wish->audio->first()->file_path) : null
                    ],
                    'position' => $wish->position,
                    'createdAt' => $wish->created_at->toISOString()
                ],
                'message' => 'Lời chúc đã được tạo thành công!'
            ], 201);

        } catch (\Exception $e) {
            \Log::error('Error creating wish: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi tạo lời chúc: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $wish = Wish::with(['media', 'user'])->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $wish->id,
                'code' => $wish->code,
                'senderName' => $wish->sender_name,
                'content' => [
                    'text' => $wish->content,
                    'images' => $wish->images->map(fn($img) => Storage::disk('wishes')->url($img->file_path)),
                    'audio' => $wish->audio->first() ? Storage::disk('wishes')->url($wish->audio->first()->file_path) : null
                ],
                'position' => $wish->position,
                'createdAt' => $wish->created_at->toISOString()
            ]
        ]);
    }

    public function updatePosition(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'position_x' => 'required|numeric|between:0,100',
            'position_y' => 'required|numeric|between:0,100',
            'rotation' => 'numeric|between:-45,45'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $wish = Wish::findOrFail($id);
        $wish->update([
            'position_x' => $request->position_x,
            'position_y' => $request->position_y,
            'rotation' => $request->rotation ?? $wish->rotation
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Vị trí đã được cập nhật'
        ]);
    }

    public function searchByCode($code)
    {
        $wish = Wish::with(['media'])
            ->where('code', $code)
            ->where('is_active', true)
            ->first();

        if (!$wish) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy lời chúc với mã này'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $wish->id,
                'code' => $wish->code,
                'senderName' => $wish->sender_name,
                'content' => [
                    'text' => $wish->content,
                    'images' => $wish->images->map(fn($img) => Storage::disk('wishes')->url($img->file_path)),
                    'audio' => $wish->audio->first() ? Storage::disk('wishes')->url($wish->audio->first()->file_path) : null
                ],
                'position' => $wish->position,
                'createdAt' => $wish->created_at->toISOString()
            ]
        ]);
    }

    public function searchById($id)
    {
        $wish = Wish::with(['media'])
            ->where('id', $id)
            ->where('is_active', true)
            ->first();

        if (!$wish) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy lời chúc với ID này'
            ], 404);
        }

        return $this->show($id);
    }

    private function saveMedia(Wish $wish, $file, string $type)
    {
        // Create year/month directory structure
        $year = date('Y');
        $month = date('m');
        $directory = "{$type}s/{$year}/{$month}";
        
        // Generate unique filename
        $fileName = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
        $filePath = "{$directory}/{$fileName}";

        // Store file using wishes disk
        Storage::disk('wishes')->putFileAs($directory, $file, $fileName);

        // Save to database
        WishMedia::create([
            'wish_id' => $wish->id,
            'type' => $type,
            'file_name' => $fileName,
            'file_path' => $filePath,
            'file_size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'original_name' => $file->getClientOriginalName()
        ]);
    }

    private function saveBase64Image(Wish $wish, string $base64Data)
    {
        try {
            // Remove data URL prefix if present
            if (strpos($base64Data, 'data:image/') === 0) {
                $data = explode(',', $base64Data);
                $base64Data = $data[1];
                
                // Extract mime type
                preg_match('/data:image\/([a-zA-Z0-9]+);/', $data[0], $matches);
                $extension = $matches[1] ?? 'jpg';
            } else {
                $extension = 'jpg';
            }

            // Decode base64
            $imageData = base64_decode($base64Data);
            
            if ($imageData === false) {
                throw new \Exception('Invalid base64 data');
            }

            // Create directory structure
            $year = date('Y');
            $month = date('m');
            $directory = "images/{$year}/{$month}";
            
            // Generate filename
            $fileName = time() . '_' . Str::random(10) . '.' . $extension;
            $filePath = "{$directory}/{$fileName}";

            // Store file
            Storage::disk('wishes')->put($filePath, $imageData);

            // Save to database
            WishMedia::create([
                'wish_id' => $wish->id,
                'type' => 'image',
                'file_name' => $fileName,
                'file_path' => $filePath,
                'file_size' => strlen($imageData),
                'mime_type' => "image/{$extension}",
                'original_name' => $fileName
            ]);

        } catch (\Exception $e) {
            \Log::error('Error saving base64 image: ' . $e->getMessage());
        }
    }

    private function saveAudioBlob(Wish $wish, string $audioBlob)
    {
        try {
            // Handle audio blob (base64 or binary data)
            if (strpos($audioBlob, 'data:audio/') === 0) {
                $data = explode(',', $audioBlob);
                $audioData = base64_decode($data[1]);
                
                // Extract mime type
                preg_match('/data:audio\/([a-zA-Z0-9]+);/', $data[0], $matches);
                $extension = $matches[1] ?? 'wav';
            } else {
                $audioData = base64_decode($audioBlob);
                $extension = 'wav';
            }

            if ($audioData === false) {
                throw new \Exception('Invalid audio data');
            }

            // Create directory structure
            $year = date('Y');
            $month = date('m');
            $directory = "audios/{$year}/{$month}";
            
            // Generate filename
            $fileName = time() . '_' . Str::random(10) . '.' . $extension;
            $filePath = "{$directory}/{$fileName}";

            // Store file
            Storage::disk('wishes')->put($filePath, $audioData);

            // Save to database
            WishMedia::create([
                'wish_id' => $wish->id,
                'type' => 'audio',
                'file_name' => $fileName,
                'file_path' => $filePath,
                'file_size' => strlen($audioData),
                'mime_type' => "audio/{$extension}",
                'original_name' => $fileName
            ]);

        } catch (\Exception $e) {
            \Log::error('Error saving audio blob: ' . $e->getMessage());
        }
    }

    private function generateRandomPosition(): array
    {
        return [
            'x' => rand(5, 85),
            'y' => rand(5, 80),
            'rotation' => rand(-20, 20)
        ];
    }

    // Cleanup unused media files
    public function cleanupMedia()
    {
        try {
            $mediaFiles = WishMedia::all();
            $diskFiles = collect(Storage::disk('wishes')->allFiles());
            
            // Find orphaned files
            $usedFiles = $mediaFiles->pluck('file_path');
            $orphanedFiles = $diskFiles->diff($usedFiles);
            
            // Delete orphaned files
            foreach ($orphanedFiles as $file) {
                Storage::disk('wishes')->delete($file);
            }

            return response()->json([
                'success' => true,
                'message' => "Đã xóa {$orphanedFiles->count()} file không sử dụng"
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi dọn dẹp: ' . $e->getMessage()
            ], 500);
        }
    }
}