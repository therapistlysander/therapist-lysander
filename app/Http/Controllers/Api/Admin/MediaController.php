<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

class MediaController extends Controller
{
    /**
     * Upload a single image and store an optimised + webp copy.
     */
    public function upload(Request $request): JsonResponse
    {
        $request->validate([
            'file'   => ['required', 'file', 'mimes:jpeg,jpg,png,webp,gif,svg', 'max:5120'],
            'folder' => ['nullable', 'string', 'max:100', 'alpha_dash'],
        ]);

        $file   = $request->file('file');
        $folder = $request->input('folder', 'uploads');
        $name   = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $path   = "{$folder}/{$name}";

        // Store original
        Storage::disk('public')->putFileAs($folder, $file, $name);

        $url = Storage::disk('public')->url($path);

        // Optionally generate a webp thumbnail for non-svg files
        $webpUrl = null;
        if (! in_array($file->getClientOriginalExtension(), ['svg', 'gif'])) {
            try {
                $webpName = Str::uuid() . '.webp';
                $webpPath = "{$folder}/{$webpName}";
                $image    = Image::read($file->getRealPath())->scale(width: 1200);
                Storage::disk('public')->put($webpPath, $image->toWebp(85));
                $webpUrl = Storage::disk('public')->url($webpPath);
            } catch (\Throwable) {
                // Non-critical — proceed without webp
            }
        }

        return response()->json([
            'url'      => $url,
            'webp_url' => $webpUrl,
            'path'     => $path,
            'name'     => $name,
            'size'     => $file->getSize(),
            'mime'     => $file->getMimeType(),
        ], 201);
    }

    /**
     * List all files in a folder.
     */
    public function index(Request $request): JsonResponse
    {
        $folder = $request->input('folder', 'uploads');
        $files  = Storage::disk('public')->files($folder);

        $media = array_map(function ($path) {
            return [
                'path'         => $path,
                'url'          => Storage::disk('public')->url($path),
                'size'         => Storage::disk('public')->size($path),
                'last_modified'=> Storage::disk('public')->lastModified($path),
            ];
        }, $files);

        return response()->json(['data' => array_values($media)]);
    }

    /**
     * Delete a file by path.
     */
    public function destroy(Request $request): JsonResponse
    {
        $request->validate([
            'path' => ['required', 'string'],
        ]);

        $path = $request->input('path');

        // Prevent path traversal
        if (Str::contains($path, '..')) {
            return response()->json(['message' => 'Invalid path.'], 422);
        }

        if (! Storage::disk('public')->exists($path)) {
            return response()->json(['message' => 'File not found.'], 404);
        }

        Storage::disk('public')->delete($path);

        return response()->json(['message' => 'File deleted.']);
    }
}
