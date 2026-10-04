<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\WebpEncoder;

class ImageController extends Controller
{
    protected string $cacheDir = 'webp-cache';

    public function show(Request $request, string $path): Response
    {
        $path = ltrim($path, '/');

        if (str_contains($path, '..')) {
            abort(404);
        }

        $disk = Storage::disk('public');

        if (!$disk->exists($path)) {
            abort(404);
        }

        $originalFullPath = $disk->path($path);
        $mtime = filemtime($originalFullPath);
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        $acceptWebp = str_contains($request->header('Accept', ''), 'image/webp');

        if (!$acceptWebp || in_array($extension, ['svg', 'gif', 'webp'])) {
            return $this->serveOriginal($originalFullPath, $extension);
        }

        $width = $request->integer('w', 0);
        $quality = $request->integer('q', 82);

        $cacheName = $this->buildCacheName($path, $mtime, $width, $quality);
        $cacheFullPath = storage_path('app/public/' . $this->cacheDir . '/' . $cacheName);

        if (!file_exists($cacheFullPath)) {
            $this->generateWebp($originalFullPath, $cacheFullPath, $width, $quality, $extension);
        }

        return response()->file($cacheFullPath, [
            'Content-Type' => 'image/webp',
            'Cache-Control' => 'public, max-age=31536000, immutable',
            'Vary' => 'Accept',
        ]);
    }

    protected function buildCacheName(string $path, int $mtime, int $width, int $quality): string
    {
        $safe = str_replace(['/', '\\'], '_', $path);
        $safe = preg_replace('/[^a-zA-Z0-9_\-\.]/', '', $safe);
        $hash = substr(md5($path . '|' . $mtime . '|' . $width . '|' . $quality), 0, 10);
        return $hash . '_' . $safe . '.webp';
    }

    protected function generateWebp(string $source, string $dest, int $width, int $quality, string $extension): void
    {
        $dir = dirname($dest);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        try {
            $manager = new ImageManager(new Driver());
            $image = $manager->read($source);

            if ($width > 0 && $image->width() > $width) {
                $image->scale(width: $width);
            }

            $encoder = new WebpEncoder(quality: $quality);
            $image->encode($encoder)->save($dest);
        } catch (\Throwable $e) {
            if (file_exists($dest)) {
                @unlink($dest);
            }
            @copy($source, $dest);
        }
    }

    protected function serveOriginal(string $fullPath, string $extension): Response
    {
        $mime = match ($extension) {
            'jpg', 'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            'svg' => 'image/svg+xml',
            default => 'application/octet-stream',
        };

        return response()->file($fullPath, [
            'Content-Type' => $mime,
            'Cache-Control' => 'public, max-age=31536000, immutable',
        ]);
    }
}
