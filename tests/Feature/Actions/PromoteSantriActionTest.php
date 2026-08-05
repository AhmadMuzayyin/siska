<?php

use App\Actions\PromoteSantriAction;
use App\Events\SantriDipromosikan;
use App\Models\Kelas;
use App\Models\Santri;
use Illuminate\Support\Facades\Event;

test('promotes a batch of santris to the target kelas and keeps santris_count accurate', function () {
    Event::fake();

    $kelasAsal = Kelas::factory()->create();
    $kelasTujuan = Kelas::factory()->create();
    $santris = Santri::factory()->count(3)->create(['kelas_id' => $kelasAsal->id]);

    app(PromoteSantriAction::class)->handle($santris, $kelasTujuan);

    expect($kelasTujuan->santris()->count())->toBe(3)
        ->and($kelasAsal->santris()->count())->toBe(0);

    Event::assertDispatchedTimes(SantriDipromosikan::class, 3);
});
