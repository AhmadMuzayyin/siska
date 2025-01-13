<?php

namespace App\Filament\Resources\SppResource\Pages;

use App\Filament\Resources\SppResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class ManageSpps extends ManageRecords
{
    protected static string $resource = SppResource::class;

    protected static ?string $title = 'Data Pembayaran SPP';

    protected $roles = ['admin', 'keuangan', 'guru'];
    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('export')
                ->label('Rekap Pembayaran')
                ->icon('phosphor-file-text')
                ->color('info')
                ->modalContent(fn($record) => view('Spp.modal'))
                ->modalSubmitAction(false)
                ->modalCancelAction(false)
                ->hidden(function () {
                    if (in_array(Auth::user()->role, $this->roles)) {
                        return false;
                    }
                    return true;
                }),
            Actions\CreateAction::make()
                ->label('Tambah Pembayaran')
                ->icon('phosphor-plus')
                ->color('success')
                ->modalHeading('Tambah Pembayaran')
                ->modalSubmitActionLabel('Tambah Pembayaran')
                ->modalCancelActionLabel('Batal')
                ->createAnother(false),
        ];
    }
}
