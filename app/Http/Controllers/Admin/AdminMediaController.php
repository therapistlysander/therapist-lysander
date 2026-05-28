<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminMediaController extends Controller
{
    public function index()
    {
        $files = collect(Storage::disk('public')->files('media'))
            ->map(function ($path) {
                return [
                    'path'     => $path,
                    'filename' => basename($path),
                    'url'      => '/storage/' . $path,
                    'size'     => Storage::disk('public')->size($path),
                    'modified' => Storage::disk('public')->lastModified($path),
                ];
            })
            ->sortByDesc('modified')
            ->values();

        return view('admin.pages.media.index', compact('files'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,gif,webp,svg,pdf|max:10240',
        ]);

        $file = $request->file('file');
        $name = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))
              . '-' . time()
              . '.' . $file->getClientOriginalExtension();

        $file->storeAs('media', $name, 'public');

        $storedPath = 'media/' . $name;

        if ($request->expectsJson()) {
            return response()->json([
                'filename' => $name,
                'url'      => '/storage/' . $storedPath,
                'size'     => Storage::disk('public')->size($storedPath),
                'modified' => Storage::disk('public')->lastModified($storedPath),
            ], 201);
        }

        return back()->with('success', 'File uploaded: ' . $name);
    }

    public function details(string $filename): JsonResponse
    {
        if (Str::contains($filename, ['/', '\\', '..'])) {
            return response()->json(['error' => 'Invalid filename.'], 400);
        }

        $path = 'media/' . $filename;

        if (!Storage::disk('public')->exists($path)) {
            return response()->json(['error' => 'File not found.'], 404);
        }

        $fullPath = Storage::disk('public')->path($path);
        $size = Storage::disk('public')->size($path);
        $modified = Storage::disk('public')->lastModified($path);
        $mime = Storage::disk('public')->mimeType($path);
        $url = '/storage/' . $path;

        $dimensions = null;
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
            $imgInfo = @getimagesize($fullPath);
            if ($imgInfo) {
                $dimensions = ['width' => $imgInfo[0], 'height' => $imgInfo[1]];
            }
        }

        return response()->json([
            'filename'   => $filename,
            'url'        => $url,
            'size'       => $size,
            'mime'       => $mime,
            'modified'   => $modified,
            'dimensions' => $dimensions,
        ]);
    }

    public function bulkDestroy(Request $request): JsonResponse
    {
        $request->validate([
            'filenames'   => 'required|array|min:1',
            'filenames.*' => 'string|max:255',
        ]);

        $deleted = 0;

        foreach ($request->input('filenames') as $filename) {
            if (Str::contains($filename, ['/', '\\', '..'])) {
                continue;
            }

            $path = 'media/' . $filename;
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
                $deleted++;
            }
        }

        return response()->json(['deleted' => $deleted]);
    }

    public function destroy(string $filename)
    {
        if (Str::contains($filename, ['/', '\\', '..'])) {
            if (request()->expectsJson()) {
                return response()->json(['error' => 'Invalid filename.'], 400);
            }
            return back()->with('error', 'Invalid filename.');
        }

        $path = 'media/' . $filename;

        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);

            if (request()->expectsJson()) {
                return response()->json(['success' => true]);
            }
            return back()->with('success', 'File deleted.');
        }

        if (request()->expectsJson()) {
            return response()->json(['error' => 'File not found.'], 404);
        }
        return back()->with('error', 'File not found.');
    }
}
