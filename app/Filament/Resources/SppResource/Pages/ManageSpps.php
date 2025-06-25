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
                ->createAnother(false)
                ->mutateFormDataUsing(function (array $data) {
                    // Cek jika duplicate_detected diset
                    if (isset($data['duplicate_detected']) && $data['duplicate_detected']) {
                        return [];
                    }

                    return $data;
                })
                ->before(function (array $data) {
                    // Jika duplicate_detected ada dan bernilai true, batalkan
                    if (isset($data['duplicate_detected']) && $data['duplicate_detected']) {
                        $this->halt();
                        return;
                    }

                    // Validasi sekali lagi untuk memastikan tidak ada duplikasi data
                    $santriId = $data['santri_id'] ?? null;
                    $semesterId = $data['semester_id'] ?? null;
                    $tanggal = $data['tanggal'] ?? null;

                    if ($santriId && $semesterId && $tanggal) {
                        // Mendapatkan bulan dari tanggal yang dipilih
                        $bulanPembayaran = date('m', strtotime($tanggal));
                        $tahunPembayaran = date('Y', strtotime($tanggal));

                        // Cek apakah santri sudah melakukan pembayaran pada bulan yang sama
                        $existingPayment = \App\Models\Spp::where('santri_id', $santriId)
                            ->where('semester_id', $semesterId)
                            ->whereMonth('tanggal', $bulanPembayaran)
                            ->whereYear('tanggal', $tahunPembayaran)
                            ->first();

                        if ($existingPayment) {
                            // Jika pembayaran sudah ada, hentikan proses create
                            \Filament\Notifications\Notification::make()
                                ->title('Pembayaran Sudah Ada')
                                ->body('Santri ini sudah melakukan pembayaran SPP pada bulan ' . date('F Y', strtotime($tanggal)) . '. Silakan tutup form ini dan edit data yang sudah ada.')
                                ->danger()
                                ->persistent()
                                ->send();

                            // Hentikan proses create
                            $this->halt();
                        }
                    }
                }),
        ];
    }
}
