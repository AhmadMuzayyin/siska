<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GuruResource\Pages;
use App\Models\Guru;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class GuruResource extends Resource
{
    protected static ?string $model = Guru::class;

    protected static ?string $navigationIcon = 'phosphor-student';

    protected static ?string $navigationGroup = 'Master Data';

    protected static ?string $navigationLabel = 'Data Guru';

    public static ?int $navigationGroupSort = 1;

    public static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nama')
                    ->required()
                    ->maxLength(255)
                    ->label('Nama Lengkap'),
                TextInput::make('alamat')
                    ->required()
                    ->maxLength(255)
                    ->label('Alamat Lengkap'),
                TextInput::make('whatsapp')
                    ->required()
                    // ->minValue(62)
                    ->minLength(12)
                    ->maxLength(15)
                    ->label('No. Whatsapp Aktif'),
                Select::make('jenis')->options([
                    'Guru' => 'Guru',
                    'Kepala Sekolah' => 'Kepala Sekolah',
                ])->required()
                    ->label('Jabatan Guru'),
                Select::make('gender')->options([
                    'Laki-laki' => 'Laki-laki',
                    'Perempuan' => 'Perempuan',
                ])->required()
                    ->label('Jenis Kelamin'),
                Select::make('status')->options([
                    'Aktif' => 'Aktif',
                    'Tidak Aktif' => 'Tidak Aktif',
                ])->required()
                    ->label('Status Aktif'),
                FileUpload::make('foto')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')
                    ->label('Nama Lengkap'),
                TextColumn::make('alamat')
                    ->label('Alamat Lengkap'),
                ImageColumn::make('foto')
                    ->label('Foto Guru'),
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
                SelectFilter::make('status')
                    ->options([
                        'Aktif' => 'Aktif',
                        'Tidak Aktif' => 'Tidak Aktif',
                    ]),
                SelectFilter::make('jenis')
                    ->options([
                        'Guru' => 'Guru',
                        'Kepala Sekolah' => 'Kepala Sekolah',
                    ]),
                SelectFilter::make('gender')
                    ->options([
                        'Laki-laki' => 'Laki-laki',
                        'Perempuan' => 'Perempuan',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Lihat')
                    ->modalHeading('Detail Guru')
                    ->modalCancelActionLabel('Tutup'),
                Tables\Actions\EditAction::make()
                    ->label('Edit')
                    ->modalHeading('Edit Data Guru')
                    ->modalSubmitActionLabel('Perbarui')
                    ->modalCancelActionLabel('Batal'),
                Tables\Actions\DeleteAction::make()
                    ->label('Hapus')
                    ->modalHeading('Hapus Data Guru')
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
            'index' => Pages\ManageGurus::route('/'),
        ];
    }
}
