<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ImageKit\ImageKit;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class ImageKitService
{
    protected ?string $publicKey;

    protected ?string $privateKey;

    protected ?string $urlEndpoint;

    protected ?ImageKit $client = null;

    public function __construct(
        ?string $publicKey = null,
        ?string $privateKey = null,
        ?string $urlEndpoint = null
    ) {
        $this->publicKey = $publicKey ?? (string) config('services.imagekit.public_key', env('IMAGEKIT_PUBLIC_KEY', ''));
        $this->privateKey = $privateKey ?? (string) config('services.imagekit.private_key', env('IMAGEKIT_PRIVATE_KEY', ''));
        $this->urlEndpoint = $urlEndpoint ?? (string) config('services.imagekit.url_endpoint', env('IMAGEKIT_URL_ENDPOINT', ''));

        if ($this->isConfigured()) {
            try {
                $this->client = new ImageKit(
                    $this->publicKey,
                    $this->privateKey,
                    $this->urlEndpoint
                );
            } catch (\Throwable $e) {
                Log::warning('ImageKit initialization error: '.$e->getMessage());
                $this->client = null;
            }
        }
    }

    /**
     * Check if ImageKit API credentials are fully configured.
     */
    public function isConfigured(): bool
    {
        return ! empty($this->publicKey) && ! empty($this->privateKey) && ! empty($this->urlEndpoint) && filter_var($this->urlEndpoint, FILTER_VALIDATE_URL);
    }

    /**
     * Get the underlying ImageKit SDK instance.
     */
    public function getClient(): ?ImageKit
    {
        return $this->client;
    }

    /**
     * Upload a file / document to ImageKit.io or fallback to local disk.
     *
     * @param  UploadedFile|TemporaryUploadedFile|string|resource  $file
     * @param  array<string>  $tags
     * @return object{url: string, fileId: string, name: string, filePath: string, isImageKit: bool}
     */
    public function upload(
        mixed $file,
        ?string $fileName = null,
        string $folder = '/siska',
        array $tags = []
    ): object {
        $cleanFolder = '/'.ltrim($folder, '/');
        $resolvedFileName = $this->resolveFileName($file, $fileName);
        $filePayload = $this->resolveFilePayload($file);

        // Upload to ImageKit if configured
        if ($this->client && $this->isConfigured()) {
            try {
                $response = $this->client->uploadFile([
                    'file' => $filePayload,
                    'fileName' => $resolvedFileName,
                    'folder' => $cleanFolder,
                    'tags' => $tags,
                    'useUniqueFileName' => true,
                ]);

                if (! empty($response->result) && empty($response->error)) {
                    $res = $response->result;

                    return (object) [
                        'url' => (string) ($res->url ?? ''),
                        'fileId' => (string) ($res->fileId ?? ''),
                        'name' => (string) ($res->name ?? $resolvedFileName),
                        'filePath' => (string) ($res->filePath ?? ''),
                        'thumbnailUrl' => (string) ($res->thumbnailUrl ?? ($res->url ?? '')),
                        'fileType' => (string) ($res->fileType ?? 'non-image'),
                        'isImageKit' => true,
                    ];
                }

                $errorMessage = is_object($response->error)
                    ? ($response->error->message ?? json_encode($response->error))
                    : (string) $response->error;

                Log::warning('ImageKit API upload failed: '.$errorMessage.'. Falling back to local storage.');
            } catch (\Throwable $e) {
                Log::warning('ImageKit exception during upload: '.$e->getMessage().'. Falling back to local storage.');
            }
        }

        // Local storage fallback
        return $this->uploadToLocalFallback($file, $resolvedFileName, $cleanFolder);
    }

    /**
     * Delete a file from ImageKit or local storage.
     */
    public function delete(string $fileIdOrPath): bool
    {
        if (empty($fileIdOrPath)) {
            return false;
        }

        if ($this->client && $this->isConfigured() && ! str_starts_with($fileIdOrPath, '/') && ! str_contains($fileIdOrPath, 'public/')) {
            try {
                $response = $this->client->deleteFile($fileIdOrPath);
                if (empty($response->error)) {
                    return true;
                }
            } catch (\Throwable $e) {
                Log::warning('ImageKit delete error: '.$e->getMessage());
            }
        }

        // Attempt local storage delete (handle both relative path and /storage/ prefix)
        $cleanPath = ltrim(preg_replace('/^\/?storage\//', '', $fileIdOrPath), '/');
        if (Storage::disk('public')->exists($cleanPath)) {
            return Storage::disk('public')->delete($cleanPath);
        }

        if (Storage::disk('public')->exists($fileIdOrPath)) {
            return Storage::disk('public')->delete($fileIdOrPath);
        }

        return false;
    }

    /**
     * Generate an ImageKit transformed URL.
     *
     * @param  array<string, mixed>  $transformations
     */
    public function url(string $pathOrUrl, array $transformations = []): string
    {
        if (empty($pathOrUrl)) {
            return '';
        }

        if ($this->client && $this->isConfigured()) {
            $options = [];
            if (str_starts_with($pathOrUrl, 'http://') || str_starts_with($pathOrUrl, 'https://')) {
                $options['src'] = $pathOrUrl;
            } else {
                $options['path'] = $pathOrUrl;
            }

            if (! empty($transformations)) {
                $options['transformation'] = [$transformations];
            }

            return $this->client->url($options);
        }

        if (str_starts_with($pathOrUrl, 'http://') || str_starts_with($pathOrUrl, 'https://')) {
            return $pathOrUrl;
        }

        return Storage::disk('public')->url($pathOrUrl);
    }

    /**
     * Resolve filename from file instance.
     */
    protected function resolveFileName(mixed $file, ?string $fileName = null): string
    {
        if (! empty($fileName)) {
            return $fileName;
        }

        if ($file instanceof UploadedFile || $file instanceof TemporaryUploadedFile) {
            $original = $file->getClientOriginalName();
            if (! empty($original)) {
                return $original;
            }
            $ext = $file->getClientOriginalExtension() ?: 'bin';

            return Str::random(20).'.'.$ext;
        }

        if (is_string($file) && file_exists($file)) {
            return basename($file);
        }

        return Str::random(20).'.bin';
    }

    /**
     * Convert file input into binary string / base64 or stream acceptable by ImageKit.
     */
    protected function resolveFilePayload(mixed $file): mixed
    {
        if ($file instanceof UploadedFile || $file instanceof TemporaryUploadedFile) {
            $realPath = $file->getRealPath();
            if ($realPath && file_exists($realPath)) {
                return base64_encode(file_get_contents($realPath) ?: '');
            }
        }

        if (is_string($file)) {
            if (file_exists($file)) {
                return base64_encode(file_get_contents($file) ?: '');
            }

            return $file; // Could be base64 string or remote URL
        }

        return $file;
    }

    /**
     * Local storage fallback handler.
     */
    protected function uploadToLocalFallback(mixed $file, string $fileName, string $folder): object
    {
        $sanitizedFolder = trim($folder, '/');

        if ($file instanceof UploadedFile || $file instanceof TemporaryUploadedFile) {
            $storedPath = $file->storeAs($sanitizedFolder, $fileName, 'public');
        } elseif (is_string($file) && file_exists($file)) {
            $storedPath = $sanitizedFolder.'/'.uniqid().'_'.$fileName;
            Storage::disk('public')->put($storedPath, file_get_contents($file) ?: '');
        } else {
            $storedPath = $sanitizedFolder.'/'.uniqid().'_'.$fileName;
            Storage::disk('public')->put($storedPath, (string) $file);
        }

        $url = Storage::disk('public')->url($storedPath);

        return (object) [
            'url' => $url,
            'fileId' => $storedPath,
            'name' => $fileName,
            'filePath' => $storedPath,
            'thumbnailUrl' => $url,
            'fileType' => 'file',
            'isImageKit' => false,
        ];
    }
}
