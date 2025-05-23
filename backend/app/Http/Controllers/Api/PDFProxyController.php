<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class PdfProxyController extends Controller
{
    /**
     * Proxy PDF files to handle CORS
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function proxy(Request $request)
    {
        $filename = $request->query('file');
        
        if (!$filename) {
            return response()->json(['error' => 'File parameter is required'], 400);
        }

        // Sanitize filename to prevent directory traversal
        $filename = basename($filename);
        
        // Construct file path
        $filePath = public_path('documents/' . $filename);
        
        // Check if file exists
        if (!file_exists($filePath)) {
            return response()->json(['error' => 'File not found'], 404);
        }
        
        // Check if it's a PDF file
        $mimeType = mime_content_type($filePath);
        if ($mimeType !== 'application/pdf') {
            return response()->json(['error' => 'Invalid file type'], 400);
        }
        
        // Read file content
        $fileContent = file_get_contents($filePath);
        
        // Return response with proper headers
        return Response::make($fileContent, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'GET, OPTIONS',
            'Access-Control-Allow-Headers' => 'Content-Type, Accept, Authorization, X-Requested-With',
            'Cache-Control' => 'public, max-age=3600'
        ]);
    }

    /**
     * Get list of available PDF files
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function list()
    {
        $documentsPath = public_path('documents');
        $files = [];
        
        if (is_dir($documentsPath)) {
            $items = scandir($documentsPath);
            
            foreach ($items as $item) {
                if ($item != '.' && $item != '..' && pathinfo($item, PATHINFO_EXTENSION) === 'pdf') {
                    $files[] = [
                        'name' => $item,
                        'size' => filesize($documentsPath . '/' . $item),
                        'modified' => filemtime($documentsPath . '/' . $item)
                    ];
                }
            }
        }
        
        return response()->json([
            'files' => $files
        ]);
    }
}