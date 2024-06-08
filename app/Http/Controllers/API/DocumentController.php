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
            'description' => 'sometimes|string|max:2000',
            'file' => 'required|file|mimes:jpg,jpeg,png,gif,svg,bmp,tiff,webp,mp4,mov,avi,wmv,flv,mkv,webm,mp3,wav,ogg,flac,aac,wma,pdf,doc,docx,xls,xlsx,ppt,pptx,txt,rtf,zip,rar,7z,tar,gz,bz2|max:10240'
        ]);

        $path = $request->file('file')->store('documents');

        $document = $request->user()->documents()->create([
            'name' => $request->name,
            'tag' => $request->tag,
            'description' => $request->description,
            'file_path' => $path,
        ]);

        return response()->json([
            'message' => 'Document uploaded successfully',
            'document' => $document,
        ], 201);
    }

    public function index(Request $request)
    {
        $documents = $request->user()->documents->sortByDesc('updated_at');

        return $documents->values()->all();
    }
}
