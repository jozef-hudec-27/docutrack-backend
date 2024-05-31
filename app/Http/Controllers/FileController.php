<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FileController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $file_path = $request->file_path;

        if (!file_exists(storage_path('app/documents/' . $file_path))) {
            return response()->json([
                'message' => 'File not found',
            ], 404);
        }

        return response()->download(storage_path('app/documents/' . $file_path));
    }
}
