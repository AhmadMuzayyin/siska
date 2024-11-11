<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SppResource\Pages;
use App\Models\Spp;
use App\Models\TahunAkademik;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

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
                Select::make('tahun_akademik_id')
                    ->label('Tahun Akademik')
                    ->relationship('tahunAkademik', 'nama')
                    ->default(function () {
                        $tahunAkademik = TahunAkademik::where('is_aktif', true)->first();
                        if ($tahunAkademik) {
                            return $tahunAkademik->id;
                        }
                    }),
                Select::make('santri_id')
                    ->label('Santri')
                    ->relationship('santri', 'nama_lengkap')
                    ->searchable()
                    ->preload(),
                DatePicker::make('tanggal')
                    ->label('Tanggal')
                    ->required(),
                TextInput::make('nominal')
                    ->label('Nominal')
                    ->required(),
                Select::make('status')
                    ->label('Status')
                    ->options([
                        'Belum Lunas' => 'Belum Lunas',
                        'Sudah Lunas' => 'Sudah Lunas',
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tahunAkademik.nama')
                    ->label('Tahun Akademik'),
                TextColumn::make('santri.nama_lengkap')
                    ->label('Santri'),
                TextColumn::make('nominal')
                    ->label('Nominal')
                    ->money('IDR'),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state) => $state == 'Belum Lunas' ? 'danger' : 'success'),
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
                    ->modalSubmitActionLabel('Hapus'),
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
}
