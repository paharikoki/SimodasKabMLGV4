<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class SoftwareController extends Controller
{
    public function downloadFile($software)
    {
        $filePath = storage_path('app/software-files/' . $software);

        if (!file_exists($filePath)) {
            abort(404, 'File not found');
        }
        
        $size = filesize($filePath);
        Log::info('File size: ' . $size . ' bytes');

        return response()->download($filePath);
    }
}