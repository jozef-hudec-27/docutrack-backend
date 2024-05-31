<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'tag' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf,doc,docx,jpeg,png,jpg|max:2048',
        ]);

        $path = $request->file('file')->store('documents');

        $document = $request->user()->documents()->create([
            'name' => $request->name,
            'tag' => $request->tag,
            'file_path' => $path,
        ]);

        return response()->json([
            'message' => 'Document uploaded successfully',
            'document' => $document,
        ], 201);
    }

    public function index(Request $request)
    {
        $documents = $request->user()->documents;

        return response()->json([
            'documents' => $documents,
        ]);
    }
}
