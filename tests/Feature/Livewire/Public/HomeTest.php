<?php

use App\Enums\GalleryType;
use App\Livewire\Public\Home;
use App\Models\Gallery;
use Livewire\Livewire;

test('it defaults to the kegiatan gallery type and can switch filters', function () {
    Gallery::factory()->create(['type' => GalleryType::Kegiatan, 'title' => 'Kegiatan Rutin']);
    Gallery::factory()->create(['type' => GalleryType::Wisata, 'title' => 'Wisata Edukasi']);

    Livewire::test(Home::class)
        ->assertSet('activeGalleryType', GalleryType::Kegiatan->value)
        ->assertSee('Kegiatan Rutin')
        ->call('setGalleryType', GalleryType::Wisata->value)
        ->assertSet('activeGalleryType', GalleryType::Wisata->value)
        ->assertSee('Wisata Edukasi');
});

test('it shows an empty state callout when a gallery type has no items', function () {
    Livewire::test(Home::class)
        ->call('setGalleryType', GalleryType::Bimbingan->value)
        ->assertSee('Belum ada galeri');
});
