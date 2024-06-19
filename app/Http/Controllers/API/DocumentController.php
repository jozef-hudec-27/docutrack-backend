<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function store(Request $request)
    {
        $allowed_file_types = [
            'jpg', 'jpeg', 'png', 'gif', 'svg', 'bmp', 'tiff', 'webp',
            'mp4', 'mov', 'avi', 'wmv', 'flv', 'mkv', 'webm',
            'mp3', 'wav', 'ogg', 'flac', 'aac', 'wma',
            'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt', 'rtf',
            'zip', 'rar', '7z', 'tar', 'gz', 'bz2',
            'py', 'js', 'java', 'cpp', 'cs', 'php', 'swift', 'rb', 'html', 'css'
        ];

        $request->validate([
            'name' => 'required|string|max:255',
            'tag' => 'required|string|max:255',
            'description' => 'sometimes|string|max:2000',
            'file' => 'required|file|mimes:' . implode(',', $allowed_file_types) . '|max:10240'
        ]);

        $path = $request->file('file')->store('documents');

        $document = $request->user()->documents()->create([
            'name' => $request->name,
            'tag' => $request->tag,
            'description' => $request->description,
            'file_path' => $path,
        ]);

        return response()->json($document, 201);
    }

    public function index(Request $request)
    {
        $name = $request->get('name');
        $tag = $request->get('tag');

        $documents = $request->user()->documents();

        if ($name) {
            $documents = $documents->where('name', 'like', '%' . strtolower($name) . '%');
        }

        if ($tag) {
            $documents = $documents->where('tag', 'like', '%' . strtolower($tag) . '%');
        }

        $per_page = ($name || $tag) ? PHP_INT_MAX : 10;

        $documents = $documents->orderBy('updated_at', 'desc')->paginate($per_page);

        return response()->json($documents);
    }

    public function update(Request $request, $id)
    {
        $document = $request->user()->documents()->find($id);

        if (!$document) {
            return response()->json([
                'message' => 'Document not found.',
            ], 404);
        }

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'tag' => 'sometimes|string|max:255',
            'description' => 'sometimes|string|max:2000',
        ]);

        $document->update($request->only(['name', 'tag', 'description']));

        return $document;
    }

    public function destroy(Request $request, $id)
    {
        $document = $request->user()->documents()->find($id);

        if (!$document) {
            return response()->json([
                'message' => 'Document not found.',
            ], 404);
        }

        $document->delete();

        return response()->json([
            'message' => 'Document deleted successfully.',
        ]);
    }
}
