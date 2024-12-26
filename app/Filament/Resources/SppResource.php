<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SppResource\Pages;
use App\Models\Guru;
use App\Models\Santri;
use App\Models\Semester;
use App\Models\Spp;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Auth;

class SppResource extends Resource
{
    protected static ?string $model = Spp::class;

    protected static ?string $navigationIcon = 'fluentui-receipt-money-20-o';

    protected static ?string $navigationGroup = 'Keuangan';

    protected static ?string $navigationLabel = 'SPP';

    public static ?int $navigationGroupSort = 3;

    public static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('semester_id')
                    ->label('Tahun Akademik')
                    ->options(function () {
                        return Semester::join('tahun_akademiks', 'tahun_akademiks.id', '=', 'semesters.tahun_akademik_id')->where('semesters.is_aktif', true)->pluck('tahun_akademiks.nama', 'semesters.id');
                    })
                    ->default(function () {
                        $semester = Semester::where('is_aktif', true)->first();
                        if ($semester) {
                            return $semester->id;
                        }
                    })
                    ->disabled()
                    ->dehydrated(),
                Select::make('santri_id')
                    ->label('Santri')
                    ->options(function () {
                        if (Auth::user()->role == 'guru') {
                            $guru_id = Guru::where('user_id', Auth::id())->first()->id;
                            return Santri::join('kelas', 'kelas.id', '=', 'santris.kelas_id')
                                ->join('wali_kelas', 'wali_kelas.kelas_id', '=', 'kelas.id')
                                ->where('wali_kelas.guru_id', $guru_id)
                                ->select('santris.id', 'santris.nama_lengkap')
                                ->pluck('nama_lengkap', 'santris.id');
                        } else {
                            return Santri::pluck('nama_lengkap', 'id');
                        }
                    })
                    ->searchable()
                    ->preload()
                    ->disableOptionWhen(function ($value, $label) {
                        $spp = Spp::where('santri_id', $value)
                            ->whereIn('status', ['Sudah Lunas', 'Bayar Cicil'])
                            ->where('created_at', '>=', now()->startOfMonth())
                            ->first();

                        return $spp;
                    }),
                DatePicker::make('tanggal')
                    ->label('Tanggal')
                    ->required(),
                TextInput::make('nominal')
                    ->label('Nominal')
                    ->required(),
                ToggleButtons::make('status')
                    ->label('Status')
                    ->inline()
                    ->options([
                        'Sudah Lunas' => 'Sudah Lunas',
                        'Bayar Cicil' => 'Bayar Cicil',
                    ])
                    ->default('Belum Lunas'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(static::getTableQuery())
            ->columns([
                TextColumn::make('semester.tahunAkademik.nama')
                    ->label('Tahun Akademik'),
                TextColumn::make('santri.nama_lengkap')
                    ->label('Santri'),
                TextColumn::make('nominal')
                    ->label('Nominal')
                    ->money('IDR'),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn($state) => $state == 'Belum Lunas' ? 'danger' : 'success'),
                TextColumn::make('tanggal')
                    ->label('Tanggal Bayar')
                    ->dateTime('d F Y'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Edit')
                    ->modalHeading('Edit Data Pembayaran SPP')
                    ->modalSubmitActionLabel('Perbarui')
                    ->modalCancelActionLabel('Batal'),
                Tables\Actions\DeleteAction::make()
                    ->label('Hapus')
                    ->modalHeading('Hapus Data Pembayaran SPP')
                    ->modalDescription('Apakah anda yakin ingin menghapus data ini?')
                    ->modalCancelActionLabel('Batal')
                    ->modalSubmitActionLabel('Hapus')
                    ->hidden(function () {
                        return Auth::user()->role == 'guru';
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageSpps::route('/'),
        ];
    }

    public static function canAccess(): bool
    {
        $user = Auth::user();

        return $user->role == 'keuangan' || $user->role == 'admin' || $user->role == 'guru';
    }
    public static function getTableQuery(): Builder
    {
        $query = static::query();

        // Filter data hanya untuk guru login
        if (Auth::user()->role == 'guru') {
            $query->whereHas('santri.kelas.waliKelas', function ($subQuery) {
                $subQuery->where('guru_id', Auth::user()->guru->id);
            });
        }

        return $query;
    }
}
