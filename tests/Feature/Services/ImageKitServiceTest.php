<?php

use App\Services\ImageKitService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

test('imagekit service detects when not configured and falls back to local storage', function () {
    Config::set('services.imagekit.public_key', null);
    Config::set('services.imagekit.private_key', null);
    Config::set('services.imagekit.url_endpoint', null);

    $service = new ImageKitService;

    expect($service->isConfigured())->toBeFalse();
    expect($service->getClient())->toBeNull();
});

test('imagekit service uploads uploaded file to local fallback when unconfigured', function () {
    Storage::fake('public');

    $service = new ImageKitService('', '', '');
    $fakeFile = UploadedFile::fake()->image('avatar.jpg');

    $result = $service->upload($fakeFile, 'custom_avatar.jpg', '/siska/users');

    expect($result)->toHaveProperty('url');
    expect($result)->toHaveProperty('filePath');
    expect($result->name)->toBe('custom_avatar.jpg');
    expect($result->isImageKit)->toBeFalse();

    Storage::disk('public')->assertExists('siska/users/custom_avatar.jpg');
});

test('imagekit service deletes file correctly from local storage', function () {
    Storage::fake('public');

    $service = new ImageKitService('', '', '');
    $fakeFile = UploadedFile::fake()->image('doc.png');

    $result = $service->upload($fakeFile, 'doc.png', '/siska/documents');
    Storage::disk('public')->assertExists('siska/documents/doc.png');

    $deleted = $service->delete($result->url);
    expect($deleted)->toBeTrue();
    Storage::disk('public')->assertMissing('siska/documents/doc.png');
});

test('imagekit service url method generates correct url with transformation parameters', function () {
    $service = new ImageKitService(
        'public_test_key',
        'private_test_key',
        'https://ik.imagekit.io/myendpoint'
    );

    expect($service->isConfigured())->toBeTrue();
    expect($service->getClient())->not->toBeNull();

    $url = $service->url('/sample.jpg', ['width' => 300, 'height' => 300]);
    expect($url)->toContain('https://ik.imagekit.io/myendpoint');
    expect($url)->toContain('tr:');
});
