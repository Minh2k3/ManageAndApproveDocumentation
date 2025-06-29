<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Log;

class PDFProxyController extends Controller
{
    /**
     * Proxy PDF files to handle CORS
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function proxy(Request $request)
    {
        $file = $request->query('file');
        
        if (!$file) {
            return response()->json(['error' => 'File parameter is required'], 400);
        }

        try {
            $filePath = $this->resolveFilePath($file);
            
            if (!$filePath || !file_exists($filePath)) {
                Log::warning("PDF file not found: {$file} -> {$filePath}");
                return response()->json(['error' => 'File not found'], 404);
            }
            
            // Security check - ensure file is within allowed directories
            $realPath = realpath($filePath);
            $allowedPaths = [
                realpath(public_path('documents')),
                realpath(storage_path('app/public')),
                realpath(public_path()),
            ];
            
            $isAllowed = false;
            foreach ($allowedPaths as $allowedPath) {
                if ($allowedPath && strpos($realPath, $allowedPath) === 0) {
                    $isAllowed = true;
                    break;
                }
            }
            
            if (!$isAllowed) {
                Log::warning("Unauthorized file access attempt: {$file}");
                return response()->json(['error' => 'Unauthorized file access'], 403);
            }
            
            // Check if it's a PDF file
            $mimeType = mime_content_type($filePath);
            if ($mimeType !== 'application/pdf') {
                return response()->json(['error' => 'Invalid file type. Only PDF files are allowed'], 400);
            }
            
            // Get file info
            $fileSize = filesize($filePath);
            $fileName = basename($filePath);
            
            // Read file content
            $fileContent = file_get_contents($filePath);
            
            if ($fileContent === false) {
                return response()->json(['error' => 'Unable to read file'], 500);
            }
            
            Log::info("PDF served successfully: {$file} ({$fileSize} bytes)");
            
            // Return response with proper headers
            return Response::make($fileContent, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Length' => $fileSize,
                'Content-Disposition' => 'inline; filename="' . $fileName . '"',
                'Access-Control-Allow-Origin' => '*',
                'Access-Control-Allow-Methods' => 'GET, OPTIONS',
                'Access-Control-Allow-Headers' => 'Content-Type, Accept, Authorization, X-Requested-With',
                'Cache-Control' => 'public, max-age=3600',
                'Accept-Ranges' => 'bytes'
            ]);
            
        } catch (\Exception $e) {
            Log::error("Error serving PDF file: {$file} - " . $e->getMessage());
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    /**
     * Handle preflight OPTIONS request
     *
     * @return \Illuminate\Http\Response
     */
    public function options()
    {
        return Response::make('', 200, [
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'GET, OPTIONS',
            'Access-Control-Allow-Headers' => 'Content-Type, Accept, Authorization, X-Requested-With',
            'Access-Control-Max-Age' => '86400'
        ]);
    }

    /**
     * Resolve file path based on different possible formats
     *
     * @param string $file
     * @return string|null
     */
    private function resolveFilePath($file)
    {
        // Remove any query parameters or fragments
        $file = parse_url($file, PHP_URL_PATH) ?: $file;
        
        // If it's a full URL, extract the path part after domain
        if (filter_var($file, FILTER_VALIDATE_URL)) {
            $urlParts = parse_url($file);
            $file = ltrim($urlParts['path'], '/');
        }
        
        // Normalize path separators
        $file = str_replace('\\', '/', $file);
        
        // Remove leading slash if present
        $file = ltrim($file, '/');
        
        // Try different possible paths in order of preference
        $possiblePaths = [
            // Direct file path (if already includes documents/certificates/)
            public_path($file),
            
            // Add documents/ prefix if not present
            public_path('documents/' . $file),
            
            // Add certificates/ prefix if in documents but missing certificates/
            public_path('documents/certificates/' . basename($file)),
            
            // Handle storage links
            storage_path('app/public/' . $file),
            
            // Handle public/documents pattern
            $this->handlePublicDocumentsPath($file),
            
            // Handle storage/documents pattern  
            $this->handleStorageDocumentsPath($file),
        ];
        
        // Return the first existing file
        foreach ($possiblePaths as $path) {
            if ($path && file_exists($path) && is_file($path)) {
                return $path;
            }
        }
        
        return null;
    }
    
    /**
     * Handle public/documents/... pattern
     *
     * @param string $file
     * @return string|null
     */
    private function handlePublicDocumentsPath($file)
    {
        if (strpos($file, 'public/documents/') === 0) {
            // Remove 'public/' prefix
            $relativePath = substr($file, 7); // Remove 'public/'
            return public_path($relativePath);
        }
        
        return null;
    }
    
    /**
     * Handle storage/documents/... pattern
     *
     * @param string $file
     * @return string|null
     */
    private function handleStorageDocumentsPath($file)
    {
        if (strpos($file, 'storage/') === 0) {
            // Handle storage/app/public/... or storage/...
            if (strpos($file, 'storage/app/public/') === 0) {
                return storage_path('app/public/' . substr($file, 18));
            } else {
                return storage_path('app/public/' . substr($file, 8));
            }
        }
        
        return null;
    }

    /**
     * Get list of available PDF files in certificates directory
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function list()
    {
        $certificatesPath = public_path('documents/certificates');
        $files = [];
        
        try {
            if (is_dir($certificatesPath)) {
                $items = scandir($certificatesPath);
                
                foreach ($items as $item) {
                    if ($item != '.' && $item != '..' && is_file($certificatesPath . '/' . $item)) {
                        $filePath = $certificatesPath . '/' . $item;
                        $extension = strtolower(pathinfo($item, PATHINFO_EXTENSION));
                        
                        if ($extension === 'pdf') {
                            $files[] = [
                                'name' => $item,
                                'path' => 'documents/certificates/' . $item,
                                'size' => filesize($filePath),
                                'size_formatted' => $this->formatFileSize(filesize($filePath)),
                                'modified' => filemtime($filePath),
                                'modified_formatted' => date('Y-m-d H:i:s', filemtime($filePath))
                            ];
                        }
                    }
                }
                
                // Sort by modified date (newest first)
                usort($files, function($a, $b) {
                    return $b['modified'] - $a['modified'];
                });
            }
            
            return response()->json([
                'success' => true,
                'count' => count($files),
                'files' => $files
            ]);
            
        } catch (\Exception $e) {
            Log::error("Error listing PDF files: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Unable to list files'
            ], 500);
        }
    }
    
    /**
     * Format file size in human readable format
     *
     * @param int $size
     * @return string
     */
    private function formatFileSize($size)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $unit = 0;
        
        while ($size >= 1024 && $unit < count($units) - 1) {
            $size /= 1024;
            $unit++;
        }
        
        return round($size, 2) . ' ' . $units[$unit];
    }
    
    /**
     * Get file info without serving the content
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function info(Request $request)
    {
        $file = $request->query('file');
        
        if (!$file) {
            return response()->json(['error' => 'File parameter is required'], 400);
        }
        
        try {
            $filePath = $this->resolveFilePath($file);
            
            if (!$filePath || !file_exists($filePath)) {
                return response()->json(['error' => 'File not found'], 404);
            }
            
            $fileInfo = [
                'name' => basename($filePath),
                'path' => $file,
                'size' => filesize($filePath),
                'size_formatted' => $this->formatFileSize(filesize($filePath)),
                'modified' => filemtime($filePath),
                'modified_formatted' => date('Y-m-d H:i:s', filemtime($filePath)),
                'mime_type' => mime_content_type($filePath),
                'exists' => true
            ];
            
            return response()->json([
                'success' => true,
                'file' => $fileInfo
            ]);
            
        } catch (\Exception $e) {
            Log::error("Error getting file info: {$file} - " . $e->getMessage());
            return response()->json(['error' => 'Unable to get file info'], 500);
        }
    }
}