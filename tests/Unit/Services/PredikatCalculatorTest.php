<?php

use App\Enums\Predikat;
use App\Services\PredikatCalculator;

test('calculates the correct predikat for every boundary value with no gaps', function (int $nilai, Predikat $expected) {
    expect(PredikatCalculator::calculate($nilai))->toBe($expected);
})->with([
    [0, Predikat::E],
    [58, Predikat::E],
    [59, Predikat::D],
    [60, Predikat::D],
    [69, Predikat::D],
    [70, Predikat::C],
    [79, Predikat::C],
    [80, Predikat::B],
    [89, Predikat::B],
    [90, Predikat::A],
    [100, Predikat::A],
]);
